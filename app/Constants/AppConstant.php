<?php

namespace App\Constants;

final class AppConstant
{
    const STORAGE = 100;
    const RETURN_FALSE = -1;
    const RETURN_TRUE = 1;
    const ROOT_FOLDER_ID = 1;
    const ROOT_FOLDER_S3_PATH = 'laravel/';

    const WRONG_KEY = 'key is wrong';

    /**
     * FOLDER S3
     * 
     */
    const FOLDER_NOT_EXIST = 'Not found the folder';
    const CAN_NOT_DELETE = 'Can not delete this folder';

    /**
     * FILE
     */
    const FILE_NOT_EXIST = 'Not found the file';
}
