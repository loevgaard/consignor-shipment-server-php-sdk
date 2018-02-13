<?php
namespace Loevgaard\Consignor\ShipmentServer\Request;

interface RequestInterface
{
    const COMMAND_SUBMIT_SHIPMENT = 'SubmitShipment';

    /**
     * TODO add constants for these commands:
    GetShipmentAlternatives
    GetPOArticles
    GetLeadGroups
    GetLeadGroupDetails
    GetDefaultShipment
    GetDGList
    GetDGEnums
    GetDGArticleNoInfoList
    GetContacts
    GetContactProfile
    AddShippingRulesPostCodeFile
    ApplyDefaultsOnShipment
    CheckHealth
    CreateLeadShipment
    CreateStack
    DeleteContact
    DeleteLine
    DeleteShippingRulesPostCodeFile
    DeleteShippingRules
    DeletePackage
    DeleteShipment
    DeleteStack
    GetBatchDetails
    GetBatchList
    GetBatchReport
    GetBatchShipments
    GetDocument
    GetDocumentList
    GetDraftCount
    GetDraftShipments
    GetDropPoints
    GetInboxCount
    GetInboxShipments
    GetInsuranceClaimURL
    GetInsurancePrice
    GetPackage
    GetPrinterFonts
    GetProducts
    GetSenderAddress
    GetShipment
    GetShipmentPrice
    GetStacks
    GetStackShipments
    GetTrackingURL
    ReprintLabels
    SaveShipment
    SubmitShipment
    TransmitShipments
    TransmitStack
    ValidateShipment
     */

    /**
     * Must return the HTTP method, i.e. POST, GET
     *
     * @return string
     */
    public function getMethod() : string;

    /**
     * Must return an array of headers for the request or an empty array if no headers should be set
     *
     * @return array
     */
    public function getHeaders() : array;

    /**
     * Must return a body array or an empty array if no body should be sent
     *
     * @return array
     */
    public function getBody() : array;

    /**
     * Must return the Consignor command string, i.e. SubmitShipment for this command: https://consignor.zendesk.com/hc/da/articles/115002128973-SubmitShipment
     *
     * @return string
     */
    public function getCommand() : string;
}