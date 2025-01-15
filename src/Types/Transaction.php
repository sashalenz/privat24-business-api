<?php

namespace Sashalenz\Privat24BusinessApi\Types;

use Carbon\Carbon;
use Sashalenz\Privat24BusinessApi\Enums\Currency;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;

#[MapInputName(CamelCaseMapper::class)]
class Transaction extends Data
{
    public function __construct(
        public string $AUT_MY_CRF, // ЄДРПОУ одержувача
        public string $AUT_MY_MFO, // МФО одержувача
        public string $AUT_MY_ACC, // рахунок одержувача
        public string $AUT_MY_NAM, // назва одержувача
        public string $AUT_MY_MFO_NAME, // банк одержувача
        public string $AUT_MY_MFO_CITY, // назва міста банку
        public string $AUT_CNTR_CRF, // ЄДРПОУ контрагента
        public string $AUT_CNTR_MFO, // МФО контрагента
        public string $AUT_CNTR_ACC, // рахунок контрагента
        public string $AUT_CNTR_NAM, // назва контрагента
        public string $AUT_CNTR_MFO_NAME, // назва банку контрагента
        public string $AUT_CNTR_MFO_CITY, // назва міста банку
        public Currency $CCY, // валюта
        public string $FL_REAL, // ознака реальності проведення (r,i)
        public string $PR_PR, // стан p - проводиться, t - сторнована, r - проведена, n - забракована
        public string $DOC_TYP, // тип пл. документа
        public string $NUM_DOC, // номер документа
        #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public Carbon $DAT_KL, // 07.01.2020", // клієнтська дата
        #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public Carbon $DAT_OD, // 07.01.2020", // дата валютування
        public string $OSND, // підстава  платежу
        public float $SUM, // сума
        public float $SUM_E, // сума в національній валюті (грн)
        public string $REF, // референс проведення
        public string $REFN, // № з/п всередині проведення
        public string $TIM_P, // час проведення
        #[WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y H:i:s')]
        public Carbon $DATE_TIME_DAT_OD_TIM_P, // 07.01.2020 02:58:00",
        public string $ID, // 557091731", // ID транзакції
        public string $TRANTYPE, // тип транзакції дебет/кредит (D, C)
        public string $DLR, // референс платежу сервісу, через який створювали платіж (payment_pack_ref - у разі створення платежу через АPI «Автоклієнт»)
        public string $TECHNICAL_TRANSACTION_ID, // 557091731_online",
        public string $UETR, // ідентифікатор тразакції
        public string $ULTMT, // тип заповненості реквізитів
        public string $PAYER_ULTMT_NCEO, // ЄДРПОУ кінцевого платника
        public string $PAYER_ULTMT_DOCUMENT, // серія, номер паспорту кінцевого платника
        public string $PAYER_ULTMT_NAME, // назва кінцевого платника
        public string $PAYER_ULTMT_COUNTRY_CODE, // код країни нерезидента кінцевого платника
        public string $RECIPIENT_ULTMT_NCEO, // ЄДРПОУ кінцевого отримувача
        public string $RECIPIENT_ULTMT_DOCUMENT, // серія, номер паспорту кінцевого отримувача
        public string $RECIPIENT_ULTMT_NAME, // назва кінцевого отримувача
        public string $RECIPIENT_ULTMT_COUNTRY_CODE, // код країни нерезидента кінцевого отримувача
        public string $STRUCT_CODE, // код виду сплати
        public string $STRUCT_TYPE, // код бюджетної класифікації
        public string $STRUCT_CATEGORY, // Інформація про податкове повідомлення (рішення)
    ) {}
}
