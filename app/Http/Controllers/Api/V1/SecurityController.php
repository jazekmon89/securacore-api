<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\AdBlockSecurity;
use App\BotSecurity;
use App\Client;
use App\ContentSecurity;
use App\DoSSecurity;
use App\ProxySecurity;
use App\SpamSecurity;
use App\SQLSecurity;
use App\SecurityLabel;
use App\Http\Controllers\Controller;
use App\Security\Variations\AdBlocker;
use App\Security\Variations\BotProtection;
use App\Security\Variations\ContentProtection;
use App\Security\Variations\DoSProtection;
use App\Security\Variations\ProxyProtection;
use App\Security\Variations\SpamProtection;
use App\Security\Variations\SQLInjectionProtection;

class SecurityController extends Controller
{

    public function canAccess(Client $client) {
        if ( !$client->id ) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid request'
            ], 400);
        }
        $referer = request()->server('HTTP_REFERER');
        $referer_parsed = parse_url( $referer );
        $clients_flag = false;
        if ( $referer != null ) {
            $client_url = parse_url( $client->url );
            if ( $client_url['host'] != $referer_parsed['host'] ) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Invalid request'
                ], 400);
            }
        }
        return true;
    }

	public function getSecurities(Client $client) {
        if ( $this->canAccess($client) ) {
            $ad_block = AdBlockSecurity::where('client_id', $client->id)->first();
            $ad_block = $ad_block ? $ad_block->detection : 0;
            $bot = BotSecurity::where('client_id', $client->id)->first();
            $bot = $bot ? $bot->badbot : 0;
            $content = ContentSecurity::where('client_id', $client->id)->first();
            $content = $content ? $content->enabled : 0;
            $dos = DoSSecurity::where('client_id', $client->id)->first();
            $dos = $dos ? $dos->security : 0;
            $proxy = ProxySecurity::where('client_id', $client->id)->first();
            $proxy = $proxy ? $proxy->proxy : 0;
            $spam = SpamSecurity::where('client_id', $client->id)->first();
            $spam = $spam ? $spam->security : 0;
            $sql = SqlSecurity::where('client_id', $client->id)->first();
            $sql = $sql ? $sql->sql_injection : 0;
            return response()->json([
                'contentProtection' => $content,
                'adBlockerProtection' => $ad_block,
                'dosProtection' => $dos,
                'proxyProtection' => $proxy,
                'sqlProtection' => $sql,
                'spamProtection' => $spam,
                'botProtection' => $bot
            ], 200);
        }
        return response()->json([], 200);
	}

    public function getProtection(Client $client, $security_variation, $model) {
        $to_return = [];
        if ( $this->canAccess($client) ) {
            $to_return = $security_variation->get($client, $model);
            if ( !is_array($to_return) ) {
                $to_return = $to_return->getAttributes();
            }
        }
        return response()->json($to_return, 200);
    }

    public function setProtection(Client $client, $security_variation, $model, $fields) {
        $to_return = [];
        if ( $this->canAccess($client) ) {
            if ( !is_array($fields) ) {
                $to_return = $security_variation->updateMainField($client, $model, $fields);
            } else {
                $to_return = $security_variation->update($client, $model, $fields);
            }
            if ( !is_array($to_return) ) {
                $to_return = $to_return->getAttributes();
            }
        }
        return response()->json($to_return, 200);
    }

    public function setContentProtectionByFunctionId(Client $client, ContentSecurity $contentSecurity, $functionId, Request $request) {
        $to_return = [];
        if ( $this->canAccess($client) ) {
            $contentProtection = new ContentProtection();
            $contentProtection = $contentProtection->updateJSONField($client, $contentSecurity, 'function', $functionId, $request);
            $to_return = get_object_vars($contentProtection);
        }
        return response()->json($to_return, 200);
    }

    public function __call($name, $arguments) {
        $gets = [
            'getContentProtection',
            'getAdBlockerProtection',
            'getDosProtection',
            'getProxyProtection',
            'getSqlProtection',
            'getSpamProtection',
            'getBotProtection'
        ];
        $sets = [
            'setContentProtection',
            'setAdBlockerProtection',
            'setDosProtection',
            'setProxyProtection',
            'setSqlProtection',
            'setSpamProtection',
            'setBotProtection'
        ];
        $security_variation = null;
        $model = null;
        $argument_2 = !empty($arguments[2]) ? $arguments[2] : null;
        switch ($name) {
            case 'getContentProtection':
                $argument_2 = 'enabled';
                $security_variation = new ContentProtection();
                $model = new ContentSecurity();
                break;
            case 'getAdBlockerProtection':
                $argument_2 = 'detection';
                $security_variation = new AdBlocker();
                $model = new AdBlockSecurity();
                break;
            case 'getDosProtection':
                $argument_2 = 'security';
                $security_variation = new DoSProtection();
                $model = new DoSSecurity();
                break;
            case 'getProxyProtection':
                $argument_2 = 'proxy';
                $security_variation = new ProxyProtection();
                $model = new ProxySecurity();
                break;
            case 'getSqlProtection':
                $argument_2 = 'sql_injection';
                $security_variation = new SQLInjectionProtection();
                $model = new SQLSecurity();
                break;
            case 'getSpamProtection':
                $argument_2 = 'security';
                $security_variation = new SpamProtection();
                $model = new SpamSecurity();
                break;
            case 'getBotProtection':
                $argument_2 = 'badbot';
                $security_variation = new BotProtection();
                $model = new BotSecurity();
                break;
            default:
                $argument_2 = 'enabled';
        }
        if ( empty($arguments[0]) ) {
            return [];
        }
        if ( in_array($name, $gets) ) {
            $client = Client::where('id', $arguments[0])->first();
            return $this->getProtection($client, $security_variation, $model);
        } else if ( in_array($name , $sets) ) {
            return $this->setProtection($client, $security_variation, $model, $argument_2);
        }
    }
}
