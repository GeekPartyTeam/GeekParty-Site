<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * Получить список работ
     * @return array
     */
    public function getWorks()
    {
        return [
            'gp1' => [
                'halffinger' => [
                    'id' => 'halfinger',
                    'name' => 'Полпальца за принцессу',
                    'width' => '480',
                    'height' => '800'

                ],
                'exorcist' => [
                    'id' => 'exorcist',
                    'name' => 'Экзорцист',
                    'width' => '480',
                    'height' => '800'
                ],
                'hungrynya' => [
                    'id' => 'hungrynya',
                    'name' => 'Голодный Ня',
                    'width' => '640',
                    'height' => '900'
                ],
            ],
            'gp2' => [
                'bankjob' => [
                    'id' => 'bankjob',
                    'name' => 'Bank Job',
                    'description' => 'Ограбление банка',
                    'width' => '800',
                    'height' => '600'
                ],
                'bearit' => [
                    'id' => 'bearit',
                    'name' => 'Bear it!',
                    'description' => 'Зарисовка на тему роковых женщин в жизни медведей',
                    'source' => 'https://github.com/kipelovets/GP2_html5oldschool',
                    'authors' => [
                        [
                            'name' => 'kipelovets',
                            'url' => 'http://vk.com/kipelovets'
                        ],
                        [
                            'name' => 'Frost',
                            'url' => 'http://vk.com/alex.frost'
                        ],
                        [
                            'name' => 'Александра Горшунова',
                            'url' => 'http://vk.com/bgdel'
                        ]
                    ],
                    'width' => '800',
                    'height' => '600'
                ],
                'mushroominvaders' => [
                    'id' => 'mushroominvaders',
                    'name' => 'Mushroom invaders',
                    'description' => 'Sci-Fi экшн об добром инопланетянине, прилетевшем на неизвестную планету, чтобы пополнить запасы грибов в своей летающей тарелке',
                    'authors' => [
                        [
                            'name' => 'Виталий Романов',
                            'url' => 'http://vk.com/id2408548'
                        ]
                    ]
                    , 'width' => '800'
                    , 'height' => '600'
                ],
                'chelyaba' => [
                    'id' => 'chelyaba',
                    'name' => 'Chelyaba',
                    'description' => 'Игра про челябинского паренька, спасающего свой город от метеоритного дождя (музыка заменена на оригинальные 8bit, написанными на геймбое)',
                    'width' => '800'
                    , 'height' => '600'
                ],
                'runner' => [
                    'id' => 'runner',
                    'name' => 'Girl City Run',
                    'description' => 'Хоррор про женщину в ночном городе, полном страшных монстров, летающих по адовым синусам и косинусам',
                    'width' => '800'
                    , 'height' => '600'
                ],
            ]
        ];
    }

    /**
     * Главная страниа
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('GeekPartyBundle:Main:index.html.twig');
    }

    /**
     * Список участников
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function peopleAction()
    {
        return $this->render('GeekPartyBundle:Main:people.html.twig');
    }

    /**
     * Страница About
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
        return $this->render('GeekPartyBundle:Main:about.html.twig');
    }

    /**
     * Список работ
     * @param $party string Идентификатор пати — gp1, gp2 или gp3
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function worksAction($party)
    {
        $works = $this->getWorks();

        $params = [
            'works' => $works,
            'party' => $party
        ];

        return $this->render('GeekPartyBundle:Main:works.html.twig', $params);
    }

    /**
     * Страница работы
     * @param $party string Идентификатор пати — gp1, gp2 или gp3
     * @param $work string Строковый идентификатор работы
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workPageAction($party, $work)
    {
        $works = $this->getWorks();

        return $this->render('GeekPartyBundle:Main:work.html.twig', ['party' => $party, 'work' => $works[$party][$work]]);
    }

    /**
     * Тестовое действие
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction()
    {
        return $this->render('GeekPartyBundle:Main:test.html.twig');
    }
}
