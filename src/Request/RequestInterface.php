<?php
namespace Loevgaard\Consignor\ShipmentServer\Request;

interface RequestInterface
{
    // @todo convert camelcase to upper snake case
    const COMMAND_GetShipmentAlternatives = 'GetShipmentAlternatives';
    const COMMAND_GetPOArticles = 'GetPOArticles';
    const COMMAND_GetLeadGroups = 'GetLeadGroups';
    const COMMAND_GetLeadGroupDetails = 'GetLeadGroupDetails';
    const COMMAND_GetDefaultShipment = 'GetDefaultShipment';
    const COMMAND_GetDGList = 'GetDGList';
    const COMMAND_GetDGEnums = 'GetDGEnums';
    const COMMAND_GetDGArticleNoInfoList = 'GetDGArticleNoInfoList';
    const COMMAND_GetContacts = 'GetContacts';
    const COMMAND_GetContactProfile = 'GetContactProfile';
    const COMMAND_AddShippingRulesPostCodeFile = 'AddShippingRulesPostCodeFile';
    const COMMAND_ApplyDefaultsOnShipment = 'ApplyDefaultsOnShipment';
    const COMMAND_CheckHealth = 'CheckHealth';
    const COMMAND_CreateLeadShipment = 'CreateLeadShipment';
    const COMMAND_CreateStack = 'CreateStack';
    const COMMAND_DeleteContact = 'DeleteContact';
    const COMMAND_DeleteLine = 'DeleteLine';
    const COMMAND_DeleteShippingRulesPostCodeFile = 'DeleteShippingRulesPostCodeFile';
    const COMMAND_DeleteShippingRules = 'DeleteShippingRules';
    const COMMAND_DeletePackage = 'DeletePackage';
    const COMMAND_DeleteShipment = 'DeleteShipment';
    const COMMAND_DeleteStack = 'DeleteStack';
    const COMMAND_GetBatchDetails = 'GetBatchDetails';
    const COMMAND_GetBatchList = 'GetBatchList';
    const COMMAND_GetBatchReport = 'GetBatchReport';
    const COMMAND_GetBatchShipments = 'GetBatchShipments';
    const COMMAND_GetDocument = 'GetDocument';
    const COMMAND_GetDocumentList = 'GetDocumentList';
    const COMMAND_GET_DRAFT_COUNT = 'GetDraftCount';
    const COMMAND_GetDraftShipments = 'GetDraftShipments';
    const COMMAND_GetDropPoints = 'GetDropPoints';
    const COMMAND_GetInboxCount = 'GetInboxCount';
    const COMMAND_GetInboxShipments = 'GetInboxShipments';
    const COMMAND_GetInsuranceClaimURL = 'GetInsuranceClaimURL';
    const COMMAND_GetInsurancePrice = 'GetInsurancePrice';
    const COMMAND_GetPackage = 'GetPackage';
    const COMMAND_GetPrinterFonts = 'GetPrinterFonts';
    const COMMAND_GetProducts = 'GetProducts';
    const COMMAND_GetSenderAddress = 'GetSenderAddress';
    const COMMAND_GetShipment = 'GetShipment';
    const COMMAND_GetShipmentPrice = 'GetShipmentPrice';
    const COMMAND_GetStacks = 'GetStacks';
    const COMMAND_GetStackShipments = 'GetStackShipments';
    const COMMAND_GetTrackingURL = 'GetTrackingURL';
    const COMMAND_ReprintLabels = 'ReprintLabels';
    const COMMAND_SaveShipment = 'SaveShipment';
    const COMMAND_SUBMIT_SHIPMENT = 'SubmitShipment';
    const COMMAND_TransmitShipments = 'TransmitShipments';
    const COMMAND_TransmitStack = 'TransmitStack';
    const COMMAND_ValidateShipment = 'ValidateShipment';

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
