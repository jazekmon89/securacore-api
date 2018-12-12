<?php

namespace App\Jobs;

use App\ChatSessionUser;
use App\ChatOnlineAgent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AgentRoundRobinAssign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $session_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($session_id)
    {
        $this->session_id = $session_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $online_agents = ChatOnlineAgent::whereNotNull('resource_id');
        if ($online_agents->exists()) {
            $next_agent = ChatOnlineAgent::whereNotNull('resource_id')->where('is_next', 1);
            $agent_id = null;
            $resource_id = null;
            if ($next_agent->exists()) {
                $next_agent = $next_agent->first();
                $agent_id = $next_agent->user_id;
                $resource_id = $next_agent->resource_id;
                $online_agents = $online_agents->where('id', '>', $agent_id);
                if ($online_agents->exists()) {
                    $next_online_agent = $online_agents->first();
                    $next_online_agent->is_next = 1;
                    $next_online_agent->save();
                    $next_agent->is_next = 0;
                    $next_agent->save();
                } else {
                    $next_agent->is_next = 1;
                    $next_agent->save();
                }
            } else {
                $online_agents = $online_agents->get();
                $next_agent = $online_agents->get(0);
                $agent_id = $next_agent->user_id;
                $resource_id = $next_agent->resource_id;
                if ($online_agents->count() > 1) {
                    $next_agent->is_next = 0;
                    $next_agent->save();
                    $next_agent = $online_agents->get(1);
                    $next_agent->is_next = 1;
                    $next_agent->save();
                } else {
                    $next_agent->is_next = 0;
                    $next_agent->save();
                }
            }
            ChatSessionUser::create([
                'chat_session_id' => $this->session_id,
                'user_id' => $agent_id,
                'resource_id' => $resource_id
            ]);
        }
    }
}
