<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function getInitialsAttribute()
    {
        // Split the name into an array of words
        $words = explode(' ', $this->name);

        // Initialize the initials variable
        $initials = '';

        // Initialize the counter variable
        $counter = 0;

        // Loop through each word
        foreach ($words as $word) {
            // Increment the counter
            $counter++;

            // Get the first letter of the word and add it to the initials string
            $initials .= strtoupper(substr($word, 0, 1));

            // If the counter is equal to 2, break out of the loop
            if ($counter == 2) {
                break;
            }
        }

        // If we have only one word, use the second letter of the word
        if ($counter !== 2) {
            $initials .= substr($words[0], 1, 1);
        }

        // Return the initials
        return $initials;
    }



    /**
     * Generate a profile avatar image based on the user's initials.
     *
     * @param int $width The width of the avatar image in pixels (default: 40).
     * @param int $height The height of the avatar image in pixels (default: 40).
     * @param int $size The font size (default: 25).
     * @return string The avatar image as a base64 string.
     */
    public function avatar(int $width = 40, int $height = 40, int $size = 25): string
    {
        // Get the user's initials
        $initials = $this->getInitialsAttribute();

        // Set the path to the font file
        $fontPath = public_path('fonts/Nunito/Nunito-Regular.ttf');

        // Set the font size, color, and background color for the initials
        $color = '#000000';
        $background = '#c9d7e8';

        // Check the cache for a previously generated image
        $key = "avatar-".$this->id."-".$width."x".$height."-".$size;
        $base64 = Cache::get($key);
        if ($base64 === null) {
            // Generate the initials image
            $image = $this->generateInitialsImage($initials, $width, $height, $fontPath, $size, $color, $background);

            // Encode the image as a base64 string
            $base64 = (string) $image->encode('data-url');

            // Cache the image for future requests
            Cache::put($key, $base64, now()->addMinutes(60));
        }

        // Return the base64 string
        return $base64;
    }




    /**
     * Generate an image with the given initials.
     *
     * @param string $initials
     * @param int $width
     * @param int $height
     * @param string $fontPath
     * @param int $size
     * @param string $color
     * @param string $background
     *
     * @return \Intervention\Image\Image
     */
    private function generateInitialsImage(string $initials, int $width, int $height, string $fontPath, int $size, string $color, string $background): InterventionImage
    {
        // Create a new image with the given width and height
        $image = Image::canvas($width, $height, $background);

        // Calculate the font size and position
        // Calculate the font size and position
        $fontSize = $size * 0.8;
        $x = $width / 2;
        $y = $height / 2;

        // Add the initials to the image
        $image->text($initials, $x, $y, function ($font) use ($fontSize, $color, $fontPath) {
            $font->file($fontPath);
            $font->size($fontSize);
            $font->color($color);
            $font->align('center');
            $font->valign('middle');
        });

        return $image;
    }
}
