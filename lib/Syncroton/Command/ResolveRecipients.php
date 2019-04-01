<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Command
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2019 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Paul Mehrer <p.mehrer@metaways.de>
 */

/**
 * class to handle ResolveRecipients command
 *
 * @package     Syncroton
 * @subpackage  Command
 */
class Syncroton_Command_ResolveRecipients implements Syncroton_Command_ICommand
{
    /**
     * the domDocucment containing the xml request from the client
     *
     * @var DOMDocument
     */
    protected $requestBody;

    protected $to = [];

    protected $startTime = null;
    protected $endTime = null;

    protected $getPictures = false;
    protected $pictureMaxSize = null;
    protected $pictureMaxNum = null;

    /**
     * constructor of this class
     *
     * @param DOMDocument              $_requestBody
     * @param Syncroton_Model_IDevice  $_device
     * @param string                   $_policyKey
     */
    public function __construct($requestBody, Syncroton_Model_IDevice $device = null, $policyKey = null)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * process the incoming data
     */
    public function handle()
    {
        $xpath = new DomXPath($this->requestBody);
        $xpath->registerNamespace('RR', 'ResolveRecipients');

        // 1 to 100 "To" elements
        $nodes = $xpath->query('/RR:ResolveRecipients/RR:To');
        if ($nodes->length === 0 || $nodes->length > 100) {
            throw new Syncroton_Exception();
        }

        for ($i = 0; $i < $ndoes->length; ++$i) {
            $this->to[] = ['query' => $nodes->item($i)->nodeValue, 'result' => null];
        }

        // optional, single options element
        $nodes = $xpath->query('/RR:ResolveRecipients/RR:Options');
        if ($nodes->length > 0) {
            if ($nodes->length > 1) {
                throw new Syncroton_Exception();
            }

            $nodes = $xpath->query('/RR:ResolveRecipients/RR:Options/RR:Availability');
            if ($nodes->length > 0) {
                // mandatory StartTime
                $nodes = $xpath->query('/RR:ResolveRecipients/RR:Options/RR:Availability/RR:StartTime');
                if ($nodes->length === 0 || $nodes->length > 1) {
                    throw new Syncroton_Exception();
                }
                $this->startTime = $nodes->item(0)->nodeValue;

                // optional EndTime
                $nodes = $xpath->query('/RR:ResolveRecipients/RR:Options/RR:Availability/RR:EndTime');
                if ($nodes->length === 1) {
                    $this->endTime = $nodes->item(0)->nodeValue;
                } elseif ($ndoes->length > 1) {
                    throw new Syncroton_Exception();
                }
            }

            // optional Picture
            $nodes = $xpath->query('/RR:ResolveRecipients/RR:Options/RR:Picture');
            if ($nodes->length === 1) {
                $this->getPictures = true;

                // optional MaxPictures
                $nodes = $xpath->query('/RR:ResolveRecipients/RR:Options/RR:Picture/RR:MaxPictures');
                if ($nodes->length === 1) {
                    $this->pictureMaxNum = (int)$nodes->item(0)->nodeValue;

                    if ($this->pictureMaxNum < 0) {
                        throw new Syncroton_Exception();
                    }
                } elseif ($ndoes->length > 1) {
                    throw new Syncroton_Exception();
                }

                // optional MaxSize
                $nodes = $xpath->query('/RR:ResolveRecipients/RR:Options/RR:Picture/RR:MaxSize');
                if ($nodes->length === 1) {
                    $this->pictureMaxSize = (int)$nodes->item(0)->nodeValue;

                    if ($this->pictureMaxSize < 0 || $this->pictureMaxSize > 102400) {
                        throw new Syncroton_Exception();
                    }
                } elseif ($ndoes->length > 1) {
                    throw new Syncroton_Exception();
                }

            } elseif ($ndoes->length > 1) {
                throw new Syncroton_Exception();
            }
        }

        /** @var Syncroton_Data_Contacts $contacts */
        $contacts = Syncroton_Data_Factory::factory(Syncroton_Data_Factory::CLASS_CONTACTS, null, null);
        foreach ($this->to as $to) {
            $to['result'] = $contacts->search($to['query']);
        }
    }

    /**
     * create the response
     *
     * @return DOMDocument
     */
    public function getResponse()
    {
        // Creates an instance of the DOMImplementation class
        $imp = new DOMImplementation();

        // Creates a DOMDocument instance
        $document = $imp->createDocument('ResolveRecipients', 'ResolveRecipients');
        $document->xmlVersion   = '1.0';
        $document->encoding     = 'UTF-8';
        $document->formatOutput = false;

        $document->documentElement->appendChild($document->createElement('Status', 1));

        foreach ($this->to as $to) {
            $response = $document->documentElement->appendChild($document->createElement('Response'));

            $response->appendChild($document->createElement('To', $to['query']));
            $response->appendChild($document->createElement('Status', 1));

            $response->appendChild($document->createElement('RecipientCount', count($to['result'])));

            foreach ($to['result'] as $result) {
                $recipient = $response->appendChild($document->createElement('Recipient'));
                $recipient->appendChild($document->createElement('Type', 'TODO'));
                $recipient->appendChild($document->createElement('DisplayName', 'TODO'));
                $recipient->appendChild($document->createElement('EmailAddress', 'TODO'));
                $recipient->appendChild($document->createElement('Availability', 'TODO'));

                if ($this->getPictures) {
                    $picture = $recipient->appendChild($document->createElement('Picture'));
                    $picture->appendChild($document->createElement('Status', 1));
                    $picture->appendChild($document->createElement('Data', 'blob'));
                }
            }
        }

        return $document;
    }

    /**
     * return headers of command
     *
     * @return array list of headers
     */
    public function getHeaders()
    {
        return array(
            'Content-Type'  => 'text/xml;charset=utf-8'
        );
    }
}
