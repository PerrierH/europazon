<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_ScrollToTop
 * @copyright Copyright (C) 2021 Magezon (https://www.magezon.com)
 */
namespace Magezon\ScrollToTop\Model\Config\Comment;

use Magento\Config\Model\Config\CommentInterface;

class ImageUpload implements CommentInterface
{
    /**
     * @var \Magento\Framework\File\Size
     */
    private $size;

    /**
     * @param \Magento\Framework\File\Size $size
     */
    public function __construct(
        \Magento\Framework\File\Size $size
    ) {
        $this->size = $size;
    }

    /**
     * Get comment for upload image
     *
     * @param string $elementValue
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getCommentText($elementValue): string
    {
        $maxSize = $this->size->getMaxFileSizeInMb();
        return __('Allowed file types: jpg, jpeg, gif, png, Maximum file size : %1M', $maxSize);
    }
}