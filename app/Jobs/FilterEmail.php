<?php

namespace App\Jobs;

use App\Models\Email;
use App\Models\EmailRule;
use App\Supports\EmailRuleSupport\EmailRuleHandler;
use App\Supports\EmailRuleSupport\Enumns\RuleOperation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FilterEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Email $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        EmailRule::all()->each(function (EmailRule $rule) {

            $shouldApply = true;

            foreach ($rule->conditions as $condition) {
                $operation = RuleOperation::tryFromName($condition['operation']);
                $attribute = EmailRuleHandler::$availableAttributes[$condition['field']];
                $value = $attribute::getAttributeValue($this->email);
                $result = $condition['reversed'] ? !$operation->execute($value, $condition['value']) : $operation->execute($value, $condition['value']);
                if (!$result) {
                    $shouldApply = false;
                }
            }

            if ($shouldApply) {
                foreach ($rule->actions as $action) {
                    $actionClass = EmailRuleHandler::$availableActions[$action['type']];
                    $actionClass::executeAction($this->email, $action['data']);
                }
            }
        });
    }
}
