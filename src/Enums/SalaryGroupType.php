<?php

namespace Sashalenz\Privat24BusinessApi\Enums;

/**
 * Kind of mass-payment project a company holds with the bank.
 *
 * A register is created against one of these, and the kind decides who may be
 * in it: SALARY and STUDENT carry a tabn (employee/student number), the other
 * two do not.
 */
enum SalaryGroupType: string
{
    case SALARY = 'SALARY';
    case STUDENT = 'STUDENT';
    case MASSPAYMENTS = 'MASSPAYMENTS';
    case HESED = 'HESED';
}
