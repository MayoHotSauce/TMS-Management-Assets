<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AssetRequest;

class AssetRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $assetRequest;

    public function __construct(AssetRequest $assetRequest)
    {
        $this->assetRequest = $assetRequest;
    }

    public function build()
    {
        return $this->view('emails.asset-request')
                    ->subject('Asset Request Approval Needed');
    }
}
