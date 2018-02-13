<?php
namespace Loevgaard\Consignor\ShipmentServer\Request;

interface RequestInterface
{
    const COMMAND_GET_SHIPMENT_ALTERNATIVES = 'GetShipmentAlternatives';
    const COMMAND_GET_PO_ARTICLES = 'GetPOArticles';
    const COMMAND_GET_LEAD_GROUPS = 'GetLeadGroups';
    const COMMAND_GET_LEAD_GROUP_DETAILS = 'GetLeadGroupDetails';
    const COMMAND_GET_DEFAULT_SHIPMENT = 'GetDefaultShipment';
    const COMMAND_GET_DG_LIST = 'GetDGList';
    const COMMAND_GET_DG_ENUMS = 'GetDGEnums';
    const COMMAND_GET_DG_ARTICLE_NO_INFO_LIST = 'GetDGArticleNoInfoList';
    const COMMAND_GET_CONTACTS = 'GetContacts';
    const COMMAND_GET_CONTACT_PROFILE = 'GetContactProfile';
    const COMMAND_ADD_SHIPPING_RULES_POST_CODE_FILE = 'AddShippingRulesPostCodeFile';
    const COMMAND_APPLY_DEFAULTS_ON_SHIPMENT = 'ApplyDefaultsOnShipment';
    const COMMAND_CHECK_HEALTH = 'CheckHealth';
    const COMMAND_CREATE_LEAD_SHIPMENT = 'CreateLeadShipment';
    const COMMAND_CREATE_STACK = 'CreateStack';
    const COMMAND_DELETE_CONTACT = 'DeleteContact';
    const COMMAND_DELETE_LINE = 'DeleteLine';
    const COMMAND_DELETE_SHIPPING_RULES_POST_CODE_FILE = 'DeleteShippingRulesPostCodeFile';
    const COMMAND_DELETE_SHIPPING_RULES = 'DeleteShippingRules';
    const COMMAND_DELETE_PACKAGE = 'DeletePackage';
    const COMMAND_DELETE_SHIPMENT = 'DeleteShipment';
    const COMMAND_DELETE_STACK = 'DeleteStack';
    const COMMAND_GET_BATCH_DETAILS = 'GetBatchDetails';
    const COMMAND_GET_BATCH_LIST = 'GetBatchList';
    const COMMAND_GET_BATCH_REPORT = 'GetBatchReport';
    const COMMAND_GET_BATCH_SHIPMENTS = 'GetBatchShipments';
    const COMMAND_GET_DOCUMENT = 'GetDocument';
    const COMMAND_GET_DOCUMENT_LIST = 'GetDocumentList';
    const COMMAND_GET_DRAFT_COUNT = 'GetDraftCount';
    const COMMAND_GET_DRAFT_SHIPMENTS = 'GetDraftShipments';
    const COMMAND_GET_DROP_POINTS = 'GetDropPoints';
    const COMMAND_GET_INBOX_COUNT = 'GetInboxCount';
    const COMMAND_GET_INBOX_SHIPMENTS = 'GetInboxShipments';
    const COMMAND_GET_INSURANCE_CLAIM_URL = 'GetInsuranceClaimURL';
    const COMMAND_GET_INSURANCE_PRICE = 'GetInsurancePrice';
    const COMMAND_GET_PACKAGE = 'GetPackage';
    const COMMAND_GET_PRINTER_FONTS = 'GetPrinterFonts';
    const COMMAND_GET_PRODUCTS = 'GetProducts';
    const COMMAND_GET_SENDER_ADDRESS = 'GetSenderAddress';
    const COMMAND_GET_SHIPMENT = 'GetShipment';
    const COMMAND_GET_SHIPMENT_PRICE = 'GetShipmentPrice';
    const COMMAND_GET_STACKS = 'GetStacks';
    const COMMAND_GET_STACK_SHIPMENTS = 'GetStackShipments';
    const COMMAND_GET_TRACKING_URL = 'GetTrackingURL';
    const COMMAND_REPRINT_LABELS = 'ReprintLabels';
    const COMMAND_SAVE_SHIPMENT = 'SaveShipment';
    const COMMAND_SUBMIT_SHIPMENT = 'SubmitShipment';
    const COMMAND_TRANSMIT_SHIPMENTS = 'TransmitShipments';
    const COMMAND_TRANSMIT_STACK = 'TransmitStack';
    const COMMAND_VALIDATE_SHIPMENT = 'ValidateShipment';

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

    /**
     * Must return the response class that matches the request, else use the generic Response class
     *
     * @return string
     */
    public function getResponseClass() : string;
}
