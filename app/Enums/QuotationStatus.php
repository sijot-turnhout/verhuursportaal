<?php 

declare(strict_types=1); 

namespace App\Enums; 

enum QuotationStatus: string 
{
    case Draft = 'Klad offerte';
    case Open = 'Openstaande offerte';
    case Accepted = 'Goedgekeurde offerte';
    case Declined = 'Afgewezen offerte';
    case Expired = 'Verlopen offerte';
}