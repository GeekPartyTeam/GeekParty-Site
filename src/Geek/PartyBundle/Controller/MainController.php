<?php

namespace Geek\PartyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
    , Symfony\Component\HttpFoundation\Response
    ;

class MainController extends Controller
{
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $now = new \DateTime();
        $showtime = new \DateTime("2013-04-14 12:00");
        $parameters['showtime'] = $now >= $showtime;
        return parent::render($view, $parameters, $response);
    }

    /**
     * Получить список работ
     * @return array
     */
    public function getWorks()
    {
        return [
            'gp1' => [
                'halfinger' => [
                    'id' => 'halfinger',
                    'name' => 'Полпальца за принцессу',
					'description' => 'Король Артур уехал в поход. Вы играете за Ланцелота. Вы должны воспользоваться отсутствием Артура и разбудить прекрасную принцессу, которую охраняет бдительный страж с топором. Эротическая мелодрамма.',
                    'width' => '480',
                    'height' => '800'

                ],
                'exorcist' => [
                    'id' => 'exorcist',
                    'name' => 'Экзорцист',
					'description' => 'Жанр хоррор. Практикуем изгнание дьявола из девочки с длинными черными волосами',
                    'width' => '480',
                    'height' => '800'
                ],
                'hungrynya' => [
                    'id' => 'hungrynya',
                    'name' => 'Голодный Ня',
					'description' => 'Накорми Няшку, да не останься без пальца',
                    'width' => '640',
                    'height' => '900'
                ],
            ],
            'gp2' => [
                'bankjob' => [
                    'id' => 'bankjob',
                    'name' => 'Bank Job',
                    'description' => 'Беглый зек решил ограбить банк и грабит его, пока не будет пойман, либо не попадет в смертельные ловушки. Игра разбита на уровни. Мега хит',
					'authors' => [
                        [
                            'name' => 'sk1e и др.',
                            'url' => 'http://vk.com/geekparty'
                        ]
                    ]
                    ,'width' => '800',
                    'height' => '480'
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
                    'width' => '960',
                    'height' => '640'
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
                    , 'width' => '1024'
                    , 'height' => '768'
                ],
                'chelyaba' => [
                    'id' => 'chelyaba',
                    'name' => 'Chelyaba',
                    'description' => 'Игра про челябинского паренька, спасающего свой город от метеоритного дождя (музыка заменена на оригинальные 8bit, написанными на геймбое)',
					'authors' => [
                        [
                            'name' => 'bl00rise, serebroff и др.',
                            'url' => 'http://vk.com/geekparty'
                        ]
                    ]
                    ,'width' => '800'
                    , 'height' => '600'
                ],
                'runner' => [
                    'id' => 'runner',
                    'name' => 'Girl City Run',
                    'description' => 'Хоррор про женщину в ночном городе, полном страшных монстров, летающих по адовым синусам и косинусам',
					'authors' => [
                        [
                            'name' => 'random + Елизавета',
                            'url' => 'http://vk.com/geekparty'
                        ]
                    ]
                    ,'width' => '800'
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
