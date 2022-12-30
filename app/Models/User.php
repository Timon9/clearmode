<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
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

    public function getAvatarAttribute():string
    {
        $initials = 'AB';
        $width = 40;
        $height = 40;
        $fontPath = public_path('fonts/Nunito/Nunito-Regular.ttf');
       // $fontPath = 5;  // Use an internal GD font

        $size = 25;
        $color = '#ffffff';
        $background = '#336699';

        $image = $this->generateInitialsImage($initials, $width, $height, $fontPath, $size, $color, $background);

        $base64 = (string) $image->encode('data-url');

        // Output the image to the browser
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
        $fontSize = $size * 0.8;
        $x = ($width - $fontSize ) ;
        $y = ($height - $fontSize) + 1;

        // Add the initials to the image
        $image->text($initials, $x, $y, function ($font) use ($fontSize, $color,$fontPath) {
            $font->file($fontPath);
            $font->size($fontSize);
            $font->color($color);
            $font->align('center');
            $font->valign('middle');
        });

        return $image;
    }
}
