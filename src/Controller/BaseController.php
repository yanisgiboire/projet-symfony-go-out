<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Status;

class BaseController extends AbstractController
{
    
    const STATUS = [
        Status::STATUS_CREATED,
        Status::STATUS_OPENED,
        Status::STATUS_CLOSED,
        Status::STATUS_ACTIVITY_IN_PROGRESS,
        Status::STATUS_PASSED,
        Status::STATUS_CANCELED,
        Status::STATUS_ARCHIVED
    ];

    
}
