<?php

namespace Sashalenz\Privat24BusinessApi\Enums;

/**
 * Where a register is in its life.
 *
 * The two that matter to a caller are at the ends: CREATED is the only state in
 * which lines can still be added or removed, and PROCESSED is the only one that
 * means the money actually moved. Everything between is somebody holding a pen.
 *
 * SIGNED_BY_ACCOUNTANT and SIGNED_BY_DIRECTOR are the halves of a two-signature
 * packet — whichever signs first, the packet waits in that state for the other.
 */
enum PacketStatus: string
{
    case CREATED = 'N';
    case VALIDATED = 'W';
    case APPROVED = 'S';
    case SIGNED_BY_ACCOUNTANT = 'SB';
    case SIGNED_BY_DIRECTOR = 'SD';
    case SIGNED = 'S$';
    case SENT = 'X';
    case IN_PROGRESS = 'P';
    case PROCESSED = 'F';
    case REJECTED = 'R';
    case DELETED = 'D';

    /** Lines may still be added or removed. */
    public function isEditable(): bool
    {
        return $this === self::CREATED;
    }

    /** Nothing more will happen to this packet. */
    public function isFinal(): bool
    {
        return in_array($this, [self::PROCESSED, self::REJECTED, self::DELETED], true);
    }
}
