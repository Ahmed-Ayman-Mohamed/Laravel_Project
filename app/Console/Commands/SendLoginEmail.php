<?php

namespace App\Console\Commands;

use App\Mail\MyTestMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SendLoginEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send {email} {name?} {message?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Via SMTP GMAIL, 
    Required Attribute {{Email}}, 
    Additional Attributes is {{Name}} and {{message}}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $message = $this->argument('message');
        $name = explode('@', $email);

        $validated = Validator::make([
            'email' => $email,
        ], [
            'email' => 'email|exists:doctors,email'
        ], [
            'email' => 'Required Available Email',
        ]);

        if ($validated->fails()) {
            foreach ($validated as $singleValidated) {
                $this->fail($singleValidated);
                return 1;
            }
        }

        try {
            Mail::to($email)->send(new MyTestMail(
                $name[0],
                $email,
                'Wellcome',
            ));
            $this->info('Email Sending Successfully');
        } catch (\Exception $e) {
            $this->error("Proplem because: " . $e->getMessage());
        }
    }
}
