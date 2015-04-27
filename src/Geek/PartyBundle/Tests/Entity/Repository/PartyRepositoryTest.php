<?php

namespace Geek\PartyBundle\Tests\Entity\Repository;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\SchemaTool;
use Geek\PartyBundle\Entity\Party;
use Geek\PartyBundle\Entity\Repository\PartyRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PartyRepositoryTest  extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Constructor
     *
     * @param string|null $name     Test name
     * @param array       $data     Test data
     * @param string      $dataName Data name
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    private static function getEntityManager()
    {
        if (!static::$kernel) {
            static::$kernel = self::createKernel(array(
                'environment' => 'test',
                'debug'       => true
            ));
        }
        static::$kernel->boot();

        $container = static::$kernel->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $stmt = $em->getConnection()->query('SELECT name FROM sqlite_master WHERE type = "table"');
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (!count($result)) {
                // Create SchemaTool
                $tool = new SchemaTool($em);
                $tool->createSchema($metadata);
            }
        } else {
            throw new SchemaException('No Metadata Classes to process.');
        }

        return $em;
    }

    public function c()
    {
        $conn = self::getEntityManager()->getConnection();
        $conn->exec("INSERT INTO Party (id, startTime, endTime, themeSubmissionStartTime, themeSubmissionEndTime, themeVotingStartTime, themeVotingEndTime, projectVotingStartTime, projectVotingEndTime)
          VALUES ('test1', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01')");
        $conn->exec("INSERT INTO Party (id, startTime, endTime, themeSubmissionStartTime, themeSubmissionEndTime, themeVotingStartTime, themeVotingEndTime, projectVotingStartTime, projectVotingEndTime)
                VALUES ('test2', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01', '2010-01-01')");
        $conn->exec("INSERT INTO Work (id, party_id, shortname, name, description, source, width, height, time) VALUES (
                1,
                'test1',
                'test1',
                'test1',
                'test1',
                '', 0, 0, '2010-01-01'
            )");
        $conn->exec("INSERT INTO Work (id, party_id, shortname, name, description, source, width, height, time) VALUES (
                2,
                'test2',
                'test2',
                'test2',
                'test2',
                '', 0, 0, '2010-01-01'
            )");
        $conn->exec("INSERT INTO ProjectVote (user_id, work_id, date, ip, userAgent, vote) VALUES (0, 1, '2010-01-01', '', '', 5)");
        $conn->exec("INSERT INTO ProjectVote (user_id, work_id, date, ip, userAgent, vote) VALUES (0, 2, '2010-01-01', '', '', 5)");
    }

    public function testGetRatings()
    {
        $this->entityManager = self::getEntityManager();
        $this->c();
        /** @var PartyRepository $repo */
        $repo = $this->entityManager->getRepository('GeekPartyBundle:Party');
        /** @var Party $party */
        $party = $repo->find('test1');
        $result = $repo->getRatings($party);
        $this->assertCount(1, $result);
    }

}