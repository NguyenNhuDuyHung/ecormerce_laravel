<?php
namespace App\Classes;

class System
{

	function config()
	{
		$data['homepage'] = [
			'label' => 'Thông tin chung',
			'description' => 'Cài đặt đầy đủ thông tin chung của website.',
			'value' => [
				'company' => ['type' => 'text', 'label' => 'Tên công ty'],
				'brand' => ['type' => 'text', 'label' => 'Tên thương hiệu'],
				'slogan' => ['type' => 'text', 'label' => 'Slogan'],
				'logo' => ['type' => 'images', 'label' => 'Logo Website', 'title' => "Click vào ô phía dưới để tải logo"],
				'favicon' => ['type' => 'images', 'label' => 'Favicon Website', 'title' => "Click vào ô phải dưới để tải favicon"],
				'copyright' => ['type' => 'text', 'label' => 'Copyright'],
				'website' => [
					'type' => 'select',
					'label' => 'Tình trạng website',
					'option' => [
						'open' => 'Mở Website',
						'close' => 'Đang đóng Website',
					]
				],
				'short_intro' => ['type' => 'editor', 'label' => "Giới thiệu ngắn"],
			]
		];

		$data['contact'] = [
			'label' => 'Thông tin liên hệ',
			'description' => 'Cài đặt đầy đủ thông tin liên hệ của website.',
			'value' => [
				'office' => ['type' => 'text', 'label' => 'Địa chi công ty'],
				'address' => ['type' => 'text', 'label' => 'Văn phòng giao dịch'],
				'hotline' => ['type' => 'text', 'label' => 'Hotline'],
				'technical_phone' => ['type' => 'text', 'label' => 'Hotline kỹ thuật'],
				'sell_phone' => ['type' => 'text', 'label' => 'Hotline kinh doanh'],
				'phone' => ['type' => 'text', 'label' => 'Số cố định'],
				'fax' => ['type' => 'text', 'label' => 'Fax'],
				'email' => ['type' => 'text', 'label' => 'Email'],
				'tax' => ['type' => 'text', 'label' => 'Mã số thuế'],
				'website' => ['type' => 'text', 'label' => 'Website'],
				'map' => [
					'type' => 'textarea',
					'label' => 'Map',
					'link' => [
						'text' => 'Hướng dẫn thiết lập bản đồ',
						'href' => '#',
						'target' => '_blank'
					],
				],
			]
		];

		$data['seo'] = [
			'label' => 'Cấu hình SEO dành cho trang chủ',
			'description' => 'Cài đặt đầy đủ thông tin SEO của trang chủ.',
			'value' => [
				'meta_title' => ['type' => 'text', 'label' => 'Tiêu đề SEO'],
				'meta_description' => ['type' => 'text', 'label' => 'Mô tả SEO'],
				'meta_keyword' => ['type' => 'text', 'label' => 'Từ khóa SEO'],
				'meta_images' => ['type' => 'images', 'label' => 'Ảnh SEO', 'title' => "Click được phải dưới để tải meta image"],
				'canonical' => ['type' => 'text', 'label' => 'Canonical'],
			]
		];

		return $data;
	}

}
