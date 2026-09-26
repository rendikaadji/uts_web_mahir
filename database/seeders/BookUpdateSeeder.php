<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            'cambridge-lower-secondary-mathematics-8' => [
                'cover_image' => '/images/books/cambridge-lower-secondary-mathematics-8.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/cambridge-lower-secondary-mathematics-8.pdf',
            ],
            'cambridge-checkpoint-science-8' => [
                'cover_image' => '/images/books/cambridge-checkpoint-science-8.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/cambridge-checkpoint-science-8.pdf',
            ],
            'fiqih-ibadah-muamalah-mts-viii' => [
                'cover_image' => '/images/books/fiqih-ibadah-muamalah-mts-viii.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/fiqih-ibadah-muamalah-mts-viii.pdf',
            ],
            'tafsir-ringkas-juz-amma-remaja' => [
                'cover_image' => '/images/books/tafsir-ringkas-juz-amma-remaja.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/tafsir-ringkas-juz-amma-remaja.pdf',
            ],
            'pemrograman-robotik-dan-iot-arduino' => [
                'cover_image' => '/images/books/pemrograman-robotik-dan-iot-arduino.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/pemrograman-robotik-dan-iot-arduino.pdf',
            ],
            'negeri-5-menara-man-jadda-wajada' => [
                'cover_image' => '/images/books/negeri-5-menara-man-jadda-wajada.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/negeri-5-menara-man-jadda-wajada.pdf',
            ],
            'ensiklopedia-peradaban-islam-golden-age' => [
                'cover_image' => '/images/books/ensiklopedia-peradaban-islam-golden-age.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/ensiklopedia-peradaban-islam-golden-age.pdf',
            ],
        ];

        foreach ($books as $slug => $data) {
            Book::where('slug', $slug)->update($data);
        }
    }
}
