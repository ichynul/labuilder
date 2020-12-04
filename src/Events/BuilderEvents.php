<?php

namespace Ichynul\Labuilder\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BuilderEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $event;

    /**
     * Undocumented variable
     *
     * @var mixed
     */
    public $object;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $event, $object = null)
    {
        $this->event = $event;
        $this->object = $object;
    }
}
