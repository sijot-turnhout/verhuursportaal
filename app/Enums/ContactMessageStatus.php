<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Enum ContactMessageStatus
 *
 * Represents the different statuses that a contact message can have within the application.
 * This enum provides a structured way to handle and track the status of contact messages
 * as they progress through the handling process.
 *
 * @package App\Enums
 */
enum ContactMessageStatus: string
{
    /**
     * New Status
     *
     * Indicates that a contact message has been received and is new.
     * No action has been taken on this message yet.
     *
     * @var string
     */
    case New = 'nieuwe contactname';

    /**
     * In Progress Status
     *
     * Indicates that a contact message is currently being handled.
     * The message is under review or action is being taken.
     *
     * @var string
     */
    case InProgress = 'in behandeling';

    /**
     * Completed Status
     *
     * Indicates that the contact message has been fully handled and resolved.
     * No further action is required for this message.
     *
     * @var string
     */
    case Completed = 'behandeld';
}
