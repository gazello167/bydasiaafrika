<?php
// Cloudflare R2 Upload Configuration
// Install AWS SDK: composer require aws/aws-sdk-php

require_once __DIR__ . '/env.php';
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function getR2Client() {
    static $client = null;
    
    if ($client === null) {
        $accountId = getenv('R2_ACCOUNT_ID') ?: '';
        $accessKey = getenv('R2_ACCESS_KEY_ID') ?: '';
        $secretKey = getenv('R2_SECRET_ACCESS_KEY') ?: '';
        
        if (empty($accountId) || empty($accessKey) || empty($secretKey)) {
            throw new Exception('R2 credentials not configured');
        }
        
        $client = new S3Client([
            'version' => 'latest',
            'region' => 'auto',
            'endpoint' => "https://{$accountId}.r2.cloudflarestorage.com",
            'credentials' => [
                'key' => $accessKey,
                'secret' => $secretKey,
            ],
            'use_path_style_endpoint' => true,
        ]);
    }
    
    return $client;
}

function uploadToR2($file, $destination) {
    $bucket = getenv('R2_BUCKET') ?: 'byd-media';
    $publicUrl = getenv('PUBLIC_R2_URL') ?: 'https://byd.gazello.my.id';
    
    try {
        $client = getR2Client();
        
        $result = $client->putObject([
            'Bucket' => $bucket,
            'Key' => $destination,
            'Body' => fopen($file['tmp_name'], 'rb'),
            'ContentType' => $file['type'],
            'ACL' => 'public-read',
        ]);
        
        return [
            'success' => true,
            'url' => "{$publicUrl}/{$destination}",
            'key' => $destination,
        ];
    } catch (AwsException $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
}

function deleteFromR2($key) {
    $bucket = getenv('R2_BUCKET') ?: 'byd-media';
    
    try {
        $client = getR2Client();
        
        $client->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key,
        ]);
        
        return ['success' => true];
    } catch (AwsException $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
}
