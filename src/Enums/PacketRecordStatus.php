<?php

namespace Sashalenz\Privat24BusinessApi\Enums;

/**
 * Where one line of a register is.
 *
 * ERROR is the one worth branching on: the bank could not validate that line
 * (see the record's errorCode) and it is still editable, so the caller can fix
 * or remove it rather than lose the whole packet.
 */
enum PacketRecordStatus: string
{
    case UNVERIFIED = 'N';
    case ERROR = 'N$';
    case VERIFIED = 'R';
    case PAID = 'M';
    case REJECTED = 'E';
}
