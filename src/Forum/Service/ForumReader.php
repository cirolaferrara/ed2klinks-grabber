<?php


namespace Ed2kLinksGrabber\Forum\Service;


use Ed2kLinksGrabber\Forum\Data\ForumData;
use Curl\Curl;

final class ForumReader
{
    private ForumData $forumData;

    /**
     * ForumReader constructor.
     *
     * @param ForumData $forumData
     */
    public function __construct(ForumData $forumData)
    {
        $this->forumData = $forumData;
    }

    /**
     * Get topics.
     *
     * @param string|null $url
     * @param int $noPages
     * @param array $ignoreList
     * @return array|null
     */
    public function getTopicsFromPage(string $url = null, int $noPages = 1, array $ignoreList = array()): ?array {
        $topics = null;

        if($this->forumData->cookies !== null && $url !== null) {
            $parsedUrl = parse_url($url);
            $baseUrl = $parsedUrl['path'].'?'.$parsedUrl['query'];

            for($i=0; $i<= $noPages; $i++) {
                $start = $noPages * $i;
                $curl = new Curl();
                $curl->setCookies($this->forumData->cookies);
                $curl->get($this->forumData->url . $baseUrl . '&start=' . $start);

                $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
                if (preg_match_all("/$regexp/siU", $curl->response, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $topicTitle = (string)$match[3];
                        $topicLink = $this->forumData->url;
                        $topicLink .= htmlspecialchars_decode($match[2]);

                        if(!in_array($topicTitle, $ignoreList)) {
                            if (strpos($match[0], 'topictitle') && strip_tags($match[3])) {
                                $topics[] = array($topicTitle, $topicLink);
                            }
                        }
                    }
                }
            }
        }

        return $topics;
    }

    /**
     * Get links.
     *
     * @param string|null $url
     * @return array|null
     */
    public function getLinksFromPage(string $url = null): ?array {
        $ed2kLinks = null;

        if($this->forumData->cookies !== null && $url !== null) {
            $parsedUrl = parse_url($url);
            $baseUrl = $parsedUrl['path'].'?'.$parsedUrl['query'];

            $curl = new Curl();
            $curl->setCookies($this->forumData->cookies);
            $curl->get($this->forumData->url.'/'.$baseUrl);

            preg_match_all('/ed2k:\/\/\|(file)\|(.+?)\|\/(?!\|)/i', $curl->response, $regex_ed2k_links);
            foreach ($regex_ed2k_links[0] as $ed2k_link) {
                $ed2kLinks[] = (string)str_replace('"', '', $ed2k_link);
            }
        }

        return $ed2kLinks;
    }
}