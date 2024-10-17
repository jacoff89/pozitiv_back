<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    public function reviews()
    {
        $photo1 = 'img/review/yr-N8SZGjko_3a2.jpg';
        $photo2 = 'img/review/yr-N8SZGjko_e21.webp';
        $photo3 = 'img/review/mBrUUARCKa8_c96.jpg';
        $photo4 = 'img/review/mBrUUARCKa8_d02.webp';
        $link = "https://vk.com/topic-112512427_34660772";

        $reviews = [
            [
                'name' => "Hamdan Ahmed-Baig",
                'text' => "You guys made me feel like it's a home and i am surrounded by big family of loving and caring people, all of you guys, you have literally made the best moment in my life forever. I would like to say special thanks to the organizer of the trip Erken who called me \"мой друг\" at first sight, I have been to many trips but never seen such a great caring person, a great organizer and a responsible man. (Вы, ребята, заставили меня почувствовать себя как дома, будто я окружен большой семьей любящих и заботливых людей, все вы, ребята, буквально сделали лучший момент в моей жизни. Я хотел бы сказать особую благодарность организатору поездки Еркену, который назвал меня \"мой друг\" с первого взгляда. Я был во многих поездках, но никогда не видел такого неравнодушного человека, великолепного Организатора с большой буквы).",
                'link' => $link,
                'img' => $photo1,
                'imgWebp' => $photo2,
            ],
            [
                'name' => "Олег Ефимов",
                'text' => 'Ребята большие молодцы. Дали крутую организацию, вкусную еду, адекватность во взаимодействиях и всепроницающую легкость. Это большая трудность - организовать времяпровождение людей так, чтобы позволить им кайфануть всем вместе. Люди разные, вайб у всех разный и нужно это услышать. "Позитив" умеет слышать. И поэтому вряд ли кто-то мог сделать этот сплав лучше, чем эти ребята!',
                'link' => $link,
                'img' => $photo3,
                'imgWebp' => $photo4,
            ]
        ];

        return response()->json($reviews);
    }
}
