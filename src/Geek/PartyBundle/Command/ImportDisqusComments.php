<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Command;

use Doctrine\ORM\EntityManager;
use Geek\PartyBundle\Entity\ArticleComment;
use Geek\PartyBundle\Entity\ProjectComment;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ImportDisqusComments extends Command
{
    const RETURN_CODE_OK = 0;
    const RETURN_CODE_ERROR = 1;

    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setName('import:disqus')
            ->addArgument('file', InputArgument::REQUIRED, 'Disqus exported comments XML file')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $usersByEmail = [];
        $users = $this->em->getRepository('GeekPartyBundle:User')->findAll();
        foreach ($users as $user) {
            if (!$user->getEmail()) {
                continue;
            }
            $usersByEmail[$user->getEmail()] = $user;
        }

        $filename = realpath($input->getArgument('file'));
        if (!is_readable($filename)) {
            $output->writeln("<error>File not found</error>");
            return self::RETURN_CODE_ERROR;
        }

        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->loadXML(file_get_contents($filename));
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('disqus', 'http://disqus.com');

        $threadUrls = [];
        foreach ($xpath->query('/disqus:disqus/disqus:thread') as $threadNode) {
            /** @var \DOMElement $threadNode */
            if ($threadNode->tagName == 'thread') {
                $id = $threadNode->getAttribute('dsq:id');
                $link = $xpath->query('disqus:link', $threadNode)->item(0)->textContent;
                $threadUrls[$id] = $link;
            }
        }
        $anyNotFound = false;
        foreach ($xpath->query('/disqus:disqus/disqus:post') as $postNode) {
            /** @var \DOMElement $postNode */
            $threadId = $xpath->query('disqus:thread/@dsq:id', $postNode)
                ->item(0)
                ->nodeValue;
            $message = $xpath->query('disqus:message', $postNode)->item(0)->nodeValue;
            $dateString = $xpath->query('disqus:createdAt', $postNode)->item(0)->nodeValue;
            $date = new \DateTime($dateString);
            $email = $xpath->query('disqus:author/disqus:email', $postNode)->item(0)->nodeValue;
            $username = @$xpath->query('disqus:author/disqus:username', $postNode)->item(0)->nodeValue;

            $threadUrl = $threadUrls[$threadId];
            $comment = null;
            if (preg_match('/.*\/article\/(\d+)/', $threadUrl, $matches)) {
                $entity = $this->em->find('GeekPartyBundle:Article', $matches[1]);
                $comment = new ArticleComment();
                $comment->setArticle($entity);
            } elseif (preg_match('/.*\/browse\/.*\/(\d+)/', $threadUrl, $matches)) {
                $entity = $this->em->find('GeekPartyBundle:Work', $matches[1]);
                $comment = new ProjectComment();
                $comment->setProject($entity);
            } elseif (preg_match('/.*\/browse\/.*\/(.+)/', $threadUrl, $matches)) {
                $entity = $this->em->getRepository('GeekPartyBundle:Work')->findOneBy(['shortname' => $matches[1]]);
                $comment = new ProjectComment();
                $comment->setProject($entity);
            }
            if (!$comment) {
                $anyNotFound = true;
                $output->writeln("Not found: $threadUrl");
                continue;
            }
            $comment->setText(strip_tags($message));
            $comment->setDate($date);
            $comment->setForeignAuthor($username);
            $this->em->persist($comment);
        }

        if (!$anyNotFound) {
            $this->em->flush();
        }

        return self::RETURN_CODE_OK;
    }
}