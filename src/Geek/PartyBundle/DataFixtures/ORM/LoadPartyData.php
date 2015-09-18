<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Geek\PartyBundle\Entity\Party;

class LoadPartyData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $party = new Party();
        $party->setId('test');
        $party->setThemeSubmissionStartTime(new \DateTime('2010-01-01'));
        $party->setThemeSubmissionEndTime(new \DateTime('2010-01-02'));
        $party->setThemeVotingStartTime(new \DateTime('2010-01-03'));
        $party->setThemeVotingEndTime(new \DateTime('2010-01-03'));
        $party->setStartTime(new \DateTime('2010-01-04'));
        $party->setEndTime(new \DateTime('2020-01-04'));
        $party->setProjectVotingStartTime(new \DateTime('2020-01-05'));
        $party->setProjectVotingEndTime(new \DateTime('2020-01-06'));
        $manager->persist($party);
        $manager->flush();
    }
}