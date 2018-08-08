<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "Egreso_Bienes_Proyectosmryinfo.php" ?>
<?php

//
// Page class
//

$Egreso_Bienes_Proyecto_summary = NULL; // Initialize page object first

class crEgreso_Bienes_Proyecto_summary extends crEgreso_Bienes_Proyecto {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{04F6FC29-9BA8-4256-B631-5194ABED24B7}";

	// Page object name
	var $PageObjName = 'Egreso_Bienes_Proyecto_summary';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $ReportLanguage;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $ReportLanguage;
		if ($this->Subheading <> "")
			return $this->Subheading;
		return "";
	}

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $grToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$grToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (Egreso_Bienes_Proyecto)
		if (!isset($GLOBALS["Egreso_Bienes_Proyecto"])) {
			$GLOBALS["Egreso_Bienes_Proyecto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Egreso_Bienes_Proyecto"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'Egreso-Bienes-Proyecto', TRUE);

		// Start timer
		if (!isset($GLOBALS["grTimer"]))
			$GLOBALS["grTimer"] = new crTimer();

		// Debug message
		ewr_LoadDebugMsg();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fEgreso_Bienes_Proyectosummary";

		// Generate report options
		$this->GenerateOptions = new crListOptions();
		$this->GenerateOptions->Tag = "div";
		$this->GenerateOptions->TagClassName = "ewGenerateOption";
	}

	//
	// Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security, $UserProfile;
		global $gsCustomExport;

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		// Setup export options

		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Security, $ReportLanguage, $ReportOptions;
		$exportid = session_id();
		$ReportTypes = array();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a class=\"ewrExportLink ewPrint\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a class=\"ewrExportLink ewExcel\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a class=\"ewrExportLink ewWord\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["word"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormWord") : "";

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a class=\"ewrExportLink ewPdf\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;

		$ReportTypes["pdf"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPdf") : "";

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_Egreso_Bienes_Proyecto\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_Egreso_Bienes_Proyecto',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["email"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormEmail") : "";
		$ReportOptions["ReportTypes"] = $ReportTypes;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fEgreso_Bienes_Proyectosummary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fEgreso_Bienes_Proyectosummary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	// Set up search options
	function SetupSearchOptions() {
		global $ReportLanguage;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = $this->FilterApplied ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fEgreso_Bienes_Proyectosummary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = TRUE && $this->FilterApplied;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->SearchOptions->HideAllOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $ReportLanguage, $EWR_EXPORT, $gsExportFile;
		global $grDashboardReport;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();
			if (ob_get_length())
				ob_end_clean();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				if (@$this->GenOptions["reporttype"] == "email") {
					$saveResponse = $this->$fn($sContent, $this->GenOptions);
					$this->WriteGenResponse($saveResponse);
				} else {
					echo $this->$fn($sContent, array());
				}
				$url = ""; // Avoid redirect
			} else {
				$saveToFile = $this->$fn($sContent, $this->GenOptions);
				if (@$this->GenOptions["reporttype"] <> "") {
					$saveUrl = ($saveToFile <> "") ? ewr_FullUrl($saveToFile, "genurl") : $ReportLanguage->Phrase("GenerateSuccess");
					$this->WriteGenResponse($saveUrl);
					$url = ""; // Avoid redirect
				}
			}
		}

		// Close connection if not in dashboard
		if (!$grDashboardReport)
			ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ewr_SaveDebugMsg();
			header("Location: " . $url);
		}
		if (!$grDashboardReport)
			exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
	var $DisplayGrps = 3; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpColumnCount = 0;
	var $SubGrpColumnCount = 0;
	var $DtlColumnCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;
	var $DetailRows = array();
	var $TopContentClass = "col-sm-12 ewTop";
	var $LeftContentClass = "ewLeft";
	var $CenterContentClass = "col-sm-12 ewCenter";
	var $RightContentClass = "ewRight";
	var $BottomContentClass = "col-sm-12 ewBottom";

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $grFormError;
		global $grDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;
		global $grDashboardReport;

		// Set field visibility for detail fields
		$this->idegreso_bienes->SetVisibility();
		$this->numero_egreso->SetVisibility();
		$this->proyecto->SetVisibility();
		$this->fecha->SetVisibility();
		$this->lugar->SetVisibility();
		$this->calle->SetVisibility();
		$this->interseccion->SetVisibility();
		$this->descripcion->SetVisibility();
		$this->usuario->SetVisibility();
		$this->cajero->SetVisibility();
		$this->cedula_cajero->SetVisibility();
		$this->total->SetVisibility();
		$this->estado->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 14;
		$nGrps = 1;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->proyecto->SelectionList = "";
		$this->proyecto->DefaultSelectionList = "";
		$this->proyecto->ValueList = "";

		// Check if search command
		$this->SearchCommand = (@$_GET["cmd"] == "search");

		// Load default filter values
		$this->LoadDefaultFilters();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Restore filter list
		$this->RestoreFilterList();

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		ewr_AddFilter($this->Filter, $sExtendedFilter);

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);

		// Search options
		$this->SetupSearchOptions();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Get total count
		$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(), $this->Filter, $this->Sort);
		$this->TotalGrps = $this->GetCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown || $grDashboardReport) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = TRUE;

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
			$this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup($this->GenOptions);

		// Set no record found message
		if ($this->TotalGrps == 0) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
		}

		// Hide export options if export/dashboard report
		if ($this->Export <> "" || $grDashboardReport)
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown/dashboard report
		if ($this->Export <> "" || $this->DrillDown || $grDashboardReport) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
			$this->GenerateOptions->HideAllOptions();
		}

		// Get current page records
		$rs = $this->GetRs($sSql, $this->StartGrp, $this->DisplayGrps);
		$this->SetupFieldCount();
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
			}
		}
	}

	// Get count
	function GetCnt($sql) {
		return $this->getRecordCount($sql);
	}

	// Get recordset
	function GetRs($wrksql, $start, $grps) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
				$this->FirstRowData = array();
				$this->FirstRowData['idegreso_bienes'] = ewr_Conv($rs->fields('idegreso_bienes'), 3);
				$this->FirstRowData['numero_egreso'] = ewr_Conv($rs->fields('numero_egreso'), 200);
				$this->FirstRowData['proyecto'] = ewr_Conv($rs->fields('proyecto'), 200);
				$this->FirstRowData['fecha'] = ewr_Conv($rs->fields('fecha'), 133);
				$this->FirstRowData['lugar'] = ewr_Conv($rs->fields('lugar'), 200);
				$this->FirstRowData['calle'] = ewr_Conv($rs->fields('calle'), 200);
				$this->FirstRowData['interseccion'] = ewr_Conv($rs->fields('interseccion'), 200);
				$this->FirstRowData['idcajeros'] = ewr_Conv($rs->fields('idcajeros'), 3);
				$this->FirstRowData['usuario'] = ewr_Conv($rs->fields('usuario'), 200);
				$this->FirstRowData['cajero'] = ewr_Conv($rs->fields('cajero'), 200);
				$this->FirstRowData['cedula_cajero'] = ewr_Conv($rs->fields('cedula_cajero'), 200);
				$this->FirstRowData['total'] = ewr_Conv($rs->fields('total'), 131);
				$this->FirstRowData['estado'] = ewr_Conv($rs->fields('estado'), 200);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->idegreso_bienes->setDbValue($rs->fields('idegreso_bienes'));
			$this->numero_egreso->setDbValue($rs->fields('numero_egreso'));
			$this->proyecto->setDbValue($rs->fields('proyecto'));
			$this->fecha->setDbValue($rs->fields('fecha'));
			$this->lugar->setDbValue($rs->fields('lugar'));
			$this->calle->setDbValue($rs->fields('calle'));
			$this->interseccion->setDbValue($rs->fields('interseccion'));
			$this->descripcion->setDbValue($rs->fields('descripcion'));
			$this->idcajeros->setDbValue($rs->fields('idcajeros'));
			$this->usuario->setDbValue($rs->fields('usuario'));
			$this->cajero->setDbValue($rs->fields('cajero'));
			$this->cedula_cajero->setDbValue($rs->fields('cedula_cajero'));
			$this->total->setDbValue($rs->fields('total'));
			$this->estado->setDbValue($rs->fields('estado'));
			$this->Val[1] = $this->idegreso_bienes->CurrentValue;
			$this->Val[2] = $this->numero_egreso->CurrentValue;
			$this->Val[3] = $this->proyecto->CurrentValue;
			$this->Val[4] = $this->fecha->CurrentValue;
			$this->Val[5] = $this->lugar->CurrentValue;
			$this->Val[6] = $this->calle->CurrentValue;
			$this->Val[7] = $this->interseccion->CurrentValue;
			$this->Val[8] = $this->descripcion->CurrentValue;
			$this->Val[9] = $this->usuario->CurrentValue;
			$this->Val[10] = $this->cajero->CurrentValue;
			$this->Val[11] = $this->cedula_cajero->CurrentValue;
			$this->Val[12] = $this->total->CurrentValue;
			$this->Val[13] = $this->estado->CurrentValue;
		} else {
			$this->idegreso_bienes->setDbValue("");
			$this->numero_egreso->setDbValue("");
			$this->proyecto->setDbValue("");
			$this->fecha->setDbValue("");
			$this->lugar->setDbValue("");
			$this->calle->setDbValue("");
			$this->interseccion->setDbValue("");
			$this->descripcion->setDbValue("");
			$this->idcajeros->setDbValue("");
			$this->usuario->setDbValue("");
			$this->cajero->setDbValue("");
			$this->cedula_cajero->setDbValue("");
			$this->total->setDbValue("");
			$this->estado->setDbValue("");
		}
	}

	// Set up starting group
	function SetUpStartGroup($options = array()) {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;
		$startGrp = (@$options["start"] <> "") ? $options["start"] : @$_GET[EWR_TABLE_START_GROUP];
		$pageNo = (@$options["pageno"] <> "") ? $options["pageno"] : @$_GET["pageno"];

		// Check for a 'start' parameter
		if ($startGrp != "") {
			$this->StartGrp = $startGrp;
			$this->setStartGroup($this->StartGrp);
		} elseif ($pageNo != "") {
			$nPageNo = $pageNo;
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		$conn = &$this->Connection();
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Build distinct values for proyecto

			if ($popupname == 'Egreso_Bienes_Proyecto_proyecto') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->proyecto, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->proyecto->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->proyecto->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->proyecto->setDbValue($rswrk->fields[0]);
					$this->proyecto->ViewValue = @$rswrk->fields[1];
					if (is_null($this->proyecto->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->proyecto->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->proyecto->ValueList, $this->proyecto->CurrentValue, $this->proyecto->ViewValue, FALSE, $this->proyecto->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->proyecto->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->proyecto->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->proyecto;
			}

			// Output data as Json
			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				if (ob_get_length())
					ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = $_POST["sel_$sName"];
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$this->PopupName = $sName;
					if (ewr_IsAdvancedFilterValue($arValues) || $arValues == EWR_INIT_VALUE)
						$this->PopupValue = $arValues;
					if (!ewr_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = @$_POST["rf_$sName"];
					$_SESSION["rt_$sName"] = @$_POST["rt_$sName"];
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ClearSessionSelection('proyecto');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get proyecto selected values

		if (is_array(@$_SESSION["sel_Egreso_Bienes_Proyecto_proyecto"])) {
			$this->LoadSelectionFromSession('proyecto');
		} elseif (@$_SESSION["sel_Egreso_Bienes_Proyecto_proyecto"] == EWR_INIT_VALUE) { // Select all
			$this->proyecto->SelectionList = "";
		}
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 3; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $rs, $Security, $ReportLanguage;
		$conn = &$this->Connection();
		if (!$this->GrandSummarySetup) { // Get Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}

			// Get total from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectAgg(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$sSql = $this->getSqlAggPfx() . $sSql . $this->getSqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandCnt[1] = $this->TotCount;
				$this->GrandCnt[2] = $this->TotCount;
				$this->GrandCnt[3] = $this->TotCount;
				$this->GrandCnt[4] = $this->TotCount;
				$this->GrandCnt[5] = $this->TotCount;
				$this->GrandCnt[6] = $this->TotCount;
				$this->GrandCnt[7] = $this->TotCount;
				$this->GrandCnt[8] = $this->TotCount;
				$this->GrandCnt[9] = $this->TotCount;
				$this->GrandCnt[10] = $this->TotCount;
				$this->GrandCnt[11] = $this->TotCount;
				$this->GrandCnt[12] = $this->TotCount;
				$this->GrandSmry[12] = $rsagg->fields("sum_total");
				$this->GrandCnt[13] = $this->TotCount;
				$rsagg->Close();
				$bGotSummary = TRUE;
			}

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL && !($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER)) { // Summary row
			ewr_PrependClass($this->RowAttrs["class"], ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : ""); // Set up row class

			// total
			$this->total->SumViewValue = $this->total->SumValue;
			$this->total->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// idegreso_bienes
			$this->idegreso_bienes->HrefValue = "";

			// numero_egreso
			$this->numero_egreso->HrefValue = "";

			// proyecto
			$this->proyecto->HrefValue = "";
			if ($this->Export == "") {
				$drillurl = $this->proyecto->DrillDownUrl;
				$drillurl = str_replace("=f0", "=" . ewr_Encrypt($this->GetDrillDownSQL($this->idegreso_bienes, "egreso_bienes_idegreso_bienes", $this->RowTotalType, -1)), $drillurl);
				$this->proyecto->LinkAttrs["title"] = ewr_JsEncode($GLOBALS["ReportLanguage"]->Phrase("ClickToDrillDown"));
				$this->proyecto->LinkAttrs["class"] = "ewDrillLink";
				$this->proyecto->LinkAttrs["onclick"] = ewr_DrillDownJs($drillurl, 'Egreso_Bienes_Proyecto_proyecto', $GLOBALS["ReportLanguage"]->TablePhrase('r_detalle_egreso_bienes', 'TblCaption'), $this->UseDrillDownPanel);
			}

			// fecha
			$this->fecha->HrefValue = "";

			// lugar
			$this->lugar->HrefValue = "";

			// calle
			$this->calle->HrefValue = "";

			// interseccion
			$this->interseccion->HrefValue = "";

			// descripcion
			$this->descripcion->HrefValue = "";

			// usuario
			$this->usuario->HrefValue = "";

			// cajero
			$this->cajero->HrefValue = "";

			// cedula_cajero
			$this->cedula_cajero->HrefValue = "";

			// total
			$this->total->HrefValue = "";

			// estado
			$this->estado->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// idegreso_bienes
			$this->idegreso_bienes->ViewValue = $this->idegreso_bienes->CurrentValue;
			$this->idegreso_bienes->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// numero_egreso
			$this->numero_egreso->ViewValue = $this->numero_egreso->CurrentValue;
			$this->numero_egreso->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// proyecto
			$this->proyecto->ViewValue = $this->proyecto->CurrentValue;
			$this->proyecto->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ewr_FormatDateTime($this->fecha->ViewValue, 0);
			$this->fecha->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// lugar
			$this->lugar->ViewValue = $this->lugar->CurrentValue;
			$this->lugar->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// calle
			$this->calle->ViewValue = $this->calle->CurrentValue;
			$this->calle->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// interseccion
			$this->interseccion->ViewValue = $this->interseccion->CurrentValue;
			$this->interseccion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// descripcion
			$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
			$this->descripcion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// usuario
			$this->usuario->ViewValue = $this->usuario->CurrentValue;
			$this->usuario->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cajero
			$this->cajero->ViewValue = $this->cajero->CurrentValue;
			$this->cajero->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// cedula_cajero
			$this->cedula_cajero->ViewValue = $this->cedula_cajero->CurrentValue;
			$this->cedula_cajero->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// total
			$this->total->ViewValue = $this->total->CurrentValue;
			$this->total->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// estado
			$this->estado->ViewValue = $this->estado->CurrentValue;
			$this->estado->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// idegreso_bienes
			$this->idegreso_bienes->HrefValue = "";

			// numero_egreso
			$this->numero_egreso->HrefValue = "";

			// proyecto
			$this->proyecto->HrefValue = "";
			if ($this->Export == "") {
				$drillurl = $this->proyecto->DrillDownUrl;
				$drillurl = str_replace("=f0", "=" . ewr_Encrypt($this->GetDrillDownSQL($this->idegreso_bienes, "egreso_bienes_idegreso_bienes", 0)), $drillurl);
				$this->proyecto->LinkAttrs["title"] = ewr_JsEncode($ReportLanguage->Phrase("ClickToDrillDown"));
				$this->proyecto->LinkAttrs["class"] = "ewDrillLink";
				$this->proyecto->LinkAttrs["onclick"] = ewr_DrillDownJs($drillurl, 'Egreso_Bienes_Proyecto_proyecto', $GLOBALS["ReportLanguage"]->TablePhrase('r_detalle_egreso_bienes', 'TblCaption'), $this->UseDrillDownPanel);
			}

			// fecha
			$this->fecha->HrefValue = "";

			// lugar
			$this->lugar->HrefValue = "";

			// calle
			$this->calle->HrefValue = "";

			// interseccion
			$this->interseccion->HrefValue = "";

			// descripcion
			$this->descripcion->HrefValue = "";

			// usuario
			$this->usuario->HrefValue = "";

			// cajero
			$this->cajero->HrefValue = "";

			// cedula_cajero
			$this->cedula_cajero->HrefValue = "";

			// total
			$this->total->HrefValue = "";

			// estado
			$this->estado->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// total
			$CurrentValue = $this->total->SumValue;
			$ViewValue = &$this->total->SumViewValue;
			$ViewAttrs = &$this->total->ViewAttrs;
			$CellAttrs = &$this->total->CellAttrs;
			$HrefValue = &$this->total->HrefValue;
			$LinkAttrs = &$this->total->LinkAttrs;
			$this->Cell_Rendered($this->total, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// idegreso_bienes
			$CurrentValue = $this->idegreso_bienes->CurrentValue;
			$ViewValue = &$this->idegreso_bienes->ViewValue;
			$ViewAttrs = &$this->idegreso_bienes->ViewAttrs;
			$CellAttrs = &$this->idegreso_bienes->CellAttrs;
			$HrefValue = &$this->idegreso_bienes->HrefValue;
			$LinkAttrs = &$this->idegreso_bienes->LinkAttrs;
			$this->Cell_Rendered($this->idegreso_bienes, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// numero_egreso
			$CurrentValue = $this->numero_egreso->CurrentValue;
			$ViewValue = &$this->numero_egreso->ViewValue;
			$ViewAttrs = &$this->numero_egreso->ViewAttrs;
			$CellAttrs = &$this->numero_egreso->CellAttrs;
			$HrefValue = &$this->numero_egreso->HrefValue;
			$LinkAttrs = &$this->numero_egreso->LinkAttrs;
			$this->Cell_Rendered($this->numero_egreso, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// proyecto
			$CurrentValue = $this->proyecto->CurrentValue;
			$ViewValue = &$this->proyecto->ViewValue;
			$ViewAttrs = &$this->proyecto->ViewAttrs;
			$CellAttrs = &$this->proyecto->CellAttrs;
			$HrefValue = &$this->proyecto->HrefValue;
			$LinkAttrs = &$this->proyecto->LinkAttrs;
			$this->Cell_Rendered($this->proyecto, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fecha
			$CurrentValue = $this->fecha->CurrentValue;
			$ViewValue = &$this->fecha->ViewValue;
			$ViewAttrs = &$this->fecha->ViewAttrs;
			$CellAttrs = &$this->fecha->CellAttrs;
			$HrefValue = &$this->fecha->HrefValue;
			$LinkAttrs = &$this->fecha->LinkAttrs;
			$this->Cell_Rendered($this->fecha, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// lugar
			$CurrentValue = $this->lugar->CurrentValue;
			$ViewValue = &$this->lugar->ViewValue;
			$ViewAttrs = &$this->lugar->ViewAttrs;
			$CellAttrs = &$this->lugar->CellAttrs;
			$HrefValue = &$this->lugar->HrefValue;
			$LinkAttrs = &$this->lugar->LinkAttrs;
			$this->Cell_Rendered($this->lugar, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// calle
			$CurrentValue = $this->calle->CurrentValue;
			$ViewValue = &$this->calle->ViewValue;
			$ViewAttrs = &$this->calle->ViewAttrs;
			$CellAttrs = &$this->calle->CellAttrs;
			$HrefValue = &$this->calle->HrefValue;
			$LinkAttrs = &$this->calle->LinkAttrs;
			$this->Cell_Rendered($this->calle, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// interseccion
			$CurrentValue = $this->interseccion->CurrentValue;
			$ViewValue = &$this->interseccion->ViewValue;
			$ViewAttrs = &$this->interseccion->ViewAttrs;
			$CellAttrs = &$this->interseccion->CellAttrs;
			$HrefValue = &$this->interseccion->HrefValue;
			$LinkAttrs = &$this->interseccion->LinkAttrs;
			$this->Cell_Rendered($this->interseccion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// descripcion
			$CurrentValue = $this->descripcion->CurrentValue;
			$ViewValue = &$this->descripcion->ViewValue;
			$ViewAttrs = &$this->descripcion->ViewAttrs;
			$CellAttrs = &$this->descripcion->CellAttrs;
			$HrefValue = &$this->descripcion->HrefValue;
			$LinkAttrs = &$this->descripcion->LinkAttrs;
			$this->Cell_Rendered($this->descripcion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// usuario
			$CurrentValue = $this->usuario->CurrentValue;
			$ViewValue = &$this->usuario->ViewValue;
			$ViewAttrs = &$this->usuario->ViewAttrs;
			$CellAttrs = &$this->usuario->CellAttrs;
			$HrefValue = &$this->usuario->HrefValue;
			$LinkAttrs = &$this->usuario->LinkAttrs;
			$this->Cell_Rendered($this->usuario, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cajero
			$CurrentValue = $this->cajero->CurrentValue;
			$ViewValue = &$this->cajero->ViewValue;
			$ViewAttrs = &$this->cajero->ViewAttrs;
			$CellAttrs = &$this->cajero->CellAttrs;
			$HrefValue = &$this->cajero->HrefValue;
			$LinkAttrs = &$this->cajero->LinkAttrs;
			$this->Cell_Rendered($this->cajero, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// cedula_cajero
			$CurrentValue = $this->cedula_cajero->CurrentValue;
			$ViewValue = &$this->cedula_cajero->ViewValue;
			$ViewAttrs = &$this->cedula_cajero->ViewAttrs;
			$CellAttrs = &$this->cedula_cajero->CellAttrs;
			$HrefValue = &$this->cedula_cajero->HrefValue;
			$LinkAttrs = &$this->cedula_cajero->LinkAttrs;
			$this->Cell_Rendered($this->cedula_cajero, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// total
			$CurrentValue = $this->total->CurrentValue;
			$ViewValue = &$this->total->ViewValue;
			$ViewAttrs = &$this->total->ViewAttrs;
			$CellAttrs = &$this->total->CellAttrs;
			$HrefValue = &$this->total->HrefValue;
			$LinkAttrs = &$this->total->LinkAttrs;
			$this->Cell_Rendered($this->total, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// estado
			$CurrentValue = $this->estado->CurrentValue;
			$ViewValue = &$this->estado->ViewValue;
			$ViewAttrs = &$this->estado->ViewAttrs;
			$CellAttrs = &$this->estado->CellAttrs;
			$HrefValue = &$this->estado->HrefValue;
			$LinkAttrs = &$this->estado->LinkAttrs;
			$this->Cell_Rendered($this->estado, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpColumnCount = 0;
		$this->SubGrpColumnCount = 0;
		$this->DtlColumnCount = 0;
		if ($this->idegreso_bienes->Visible) $this->DtlColumnCount += 1;
		if ($this->numero_egreso->Visible) $this->DtlColumnCount += 1;
		if ($this->proyecto->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha->Visible) $this->DtlColumnCount += 1;
		if ($this->lugar->Visible) $this->DtlColumnCount += 1;
		if ($this->calle->Visible) $this->DtlColumnCount += 1;
		if ($this->interseccion->Visible) $this->DtlColumnCount += 1;
		if ($this->descripcion->Visible) $this->DtlColumnCount += 1;
		if ($this->usuario->Visible) $this->DtlColumnCount += 1;
		if ($this->cajero->Visible) $this->DtlColumnCount += 1;
		if ($this->cedula_cajero->Visible) $this->DtlColumnCount += 1;
		if ($this->total->Visible) $this->DtlColumnCount += 1;
		if ($this->estado->Visible) $this->DtlColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("summary", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $ReportOptions;
		$ReportTypes = $ReportOptions["ReportTypes"];
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
		if ($item->Visible)
			$ReportTypes["pdf"] = $ReportLanguage->Phrase("ReportFormPdf");
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a class=\"ewrExportLink ewPdf\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$ReportOptions["ReportTypes"] = $ReportTypes;
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $grFormError;
		$sFilter = "";
		if ($this->DrillDown)
			return "";
		$bPostBack = ewr_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

			// Set/clear dropdown for field proyecto
			if ($this->PopupName == 'Egreso_Bienes_Proyecto_proyecto' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->proyecto->DropDownValue = EWR_ALL_VALUE;
				else
					$this->proyecto->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'Egreso_Bienes_Proyecto_proyecto') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'proyecto');
			}

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->proyecto->DropDownValue, $this->proyecto->SearchOperator, 'proyecto'); // Field proyecto

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field proyecto
			if ($this->GetDropDownValue($this->proyecto)) {
				$bSetupFilter = TRUE;
			} elseif ($this->proyecto->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_Egreso_Bienes_Proyecto_proyecto'])) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->proyecto); // Field proyecto
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->proyecto, $sFilter, $this->proyecto->SearchOperator, FALSE, TRUE); // Field proyecto

		// Save parms to session
		$this->SetSessionDropDownValue($this->proyecto->DropDownValue, $this->proyecto->SearchOperator, 'proyecto'); // Field proyecto

		// Setup filter
		if ($bSetupFilter) {

			// Field proyecto
			$sWrk = "";
			$this->BuildDropDownFilter($this->proyecto, $sWrk, $this->proyecto->SearchOperator);
			ewr_LoadSelectionFromFilter($this->proyecto, $sWrk, $this->proyecto->SelectionList, $this->proyecto->DropDownValue);
			$_SESSION['sel_Egreso_Bienes_Proyecto_proyecto'] = ($this->proyecto->SelectionList == "") ? EWR_INIT_VALUE : $this->proyecto->SelectionList;
		}

		// Field proyecto
		ewr_LoadDropDownList($this->proyecto->DropDownList, $this->proyecto->DropDownValue);
		return $sFilter;
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr, $Default = FALSE, $SaveFilter = FALSE) {
		$FldVal = ($Default) ? $fld->DefaultDropDownValue : $fld->DropDownValue;
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownFilter($fld, $val, $FldOpr);

				// Call Page Filtering event
				if (substr($val, 0, 2) <> "@@")
					$this->Page_Filtering($fld, $sWrk, "dropdown", $FldOpr, $val);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownFilter($fld, $FldVal, $FldOpr);

			// Call Page Filtering event
			if (substr($FldVal, 0, 2) <> "@@")
				$this->Page_Filtering($fld, $sSql, "dropdown", $FldOpr, $FldVal);
		}
		if ($sSql <> "") {
			ewr_AddFilter($FilterClause, $sSql);
			if ($SaveFilter) $fld->CurrentFilter = $sSql;
		}
	}

	function GetDropDownFilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDelimiter = $fld->FldDelimiter;
		$FldVal = strval($FldVal);
		if ($FldOpr == "") $FldOpr = "=";
		$sWrk = "";
		if (ewr_SameStr($FldVal, EWR_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif (ewr_SameStr($FldVal, EWR_NOT_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NOT NULL";
		} elseif (ewr_SameStr($FldVal, EWR_EMPTY_VALUE)) {
			$sWrk = $FldExpression . " = ''";
		} elseif (ewr_SameStr($FldVal, EWR_ALL_VALUE)) {
			$sWrk = "1 = 1";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = $this->GetCustomFilter($fld, $FldVal, $this->DBID);
			} elseif ($FldDelimiter <> "" && trim($FldVal) <> "" && ($FldDataType == EWR_DATATYPE_STRING || $FldDataType == EWR_DATATYPE_MEMO)) {
				$sWrk = ewr_GetMultiSearchSql($FldExpression, trim($FldVal), $this->DBID);
			} else {
				if ($FldVal <> "" && $FldVal <> EWR_INIT_VALUE) {
					if ($FldDataType == EWR_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = ewr_DateFilterString($FldExpression, $FldOpr, $FldVal, $FldDataType, $this->DBID);
					} else {
						$sWrk = ewr_FilterString($FldOpr, $FldVal, $FldDataType, $this->DBID);
						if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
					}
				}
			}
		}
		return $sWrk;
	}

	// Get custom filter
	function GetCustomFilter(&$fld, $FldVal, $dbid = 0) {
		$sWrk = "";
		if (is_array($fld->AdvancedFilters)) {
			foreach ($fld->AdvancedFilters as $filter) {
				if ($filter->ID == $FldVal && $filter->Enabled) {
					$sFld = $fld->FldExpression;
					$sFn = $filter->FunctionName;
					$wrkid = (substr($filter->ID, 0, 2) == "@@") ? substr($filter->ID,2) : $filter->ID;
					if ($sFn <> "")
						$sWrk = $sFn($sFld, $dbid);
					else
						$sWrk = "";
					$this->Page_Filtering($fld, $sWrk, "custom", $wrkid);
					break;
				}
			}
		}
		return $sWrk;
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause, $Default = FALSE, $SaveFilter = FALSE) {
		$sWrk = ewr_GetExtendedFilter($fld, $Default, $this->DBID);
		if (!$Default)
			$this->Page_Filtering($fld, $sWrk, "extended", $fld->SearchOperator, $fld->SearchValue, $fld->SearchCondition, $fld->SearchOperator2, $fld->SearchValue2);
		if ($sWrk <> "") {
			ewr_AddFilter($FilterClause, $sWrk);
			if ($SaveFilter) $fld->CurrentFilter = $sWrk;
		}
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["so_$parm"]))
			$fld->SearchOperator = @$_GET["so_$parm"];
		if (isset($_GET["sv_$parm"])) {
			$fld->DropDownValue = @$_GET["sv_$parm"];
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv_$parm"])) {
			$fld->SearchValue = @$_GET["sv_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["so_$parm"])) {
			$fld->SearchOperator = @$_GET["so_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = @$_GET["sc_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = @$_GET["sv2_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = $_GET["so2_$parm"];
			$got = TRUE;
		}
		return $got;
	}

	// Set default ext filter
	function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2) {
		$fld->DefaultSearchValue = $sv1; // Default ext filter value 1
		$fld->DefaultSearchValue2 = $sv2; // Default ext filter value 2 (if operator 2 is enabled)
		$fld->DefaultSearchOperator = $so1; // Default search operator 1
		$fld->DefaultSearchOperator2 = $so2; // Default search operator 2 (if operator 2 is enabled)
		$fld->DefaultSearchCondition = $sc; // Default search condition (if operator 2 is enabled)
	}

	// Apply default ext filter
	function ApplyDefaultExtFilter(&$fld) {
		$fld->SearchValue = $fld->DefaultSearchValue;
		$fld->SearchValue2 = $fld->DefaultSearchValue2;
		$fld->SearchOperator = $fld->DefaultSearchOperator;
		$fld->SearchOperator2 = $fld->DefaultSearchOperator2;
		$fld->SearchCondition = $fld->DefaultSearchCondition;
	}

	// Check if Text Filter applied
	function TextFilterApplied(&$fld) {
		return (strval($fld->SearchValue) <> strval($fld->DefaultSearchValue) ||
			strval($fld->SearchValue2) <> strval($fld->DefaultSearchValue2) ||
			(strval($fld->SearchValue) <> "" &&
				strval($fld->SearchOperator) <> strval($fld->DefaultSearchOperator)) ||
			(strval($fld->SearchValue2) <> "" &&
				strval($fld->SearchOperator2) <> strval($fld->DefaultSearchOperator2)) ||
			strval($fld->SearchCondition) <> strval($fld->DefaultSearchCondition));
	}

	// Check if Non-Text Filter applied
	function NonTextFilterApplied(&$fld) {
		if (is_array($fld->DropDownValue)) {
			if (is_array($fld->DefaultDropDownValue)) {
				if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
					return TRUE;
				else
					return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
			} else {
				return TRUE;
			}
		} else {
			if (is_array($fld->DefaultDropDownValue))
				return TRUE;
			else
				$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWR_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWR_INIT_VALUE || $v2 == EWR_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_Egreso_Bienes_Proyecto_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_Egreso_Bienes_Proyecto_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_Egreso_Bienes_Proyecto_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_Egreso_Bienes_Proyecto_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_Egreso_Bienes_Proyecto_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_Egreso_Bienes_Proyecto_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_Egreso_Bienes_Proyecto_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_Egreso_Bienes_Proyecto_' . $parm] = $sv;
		$_SESSION['so_Egreso_Bienes_Proyecto_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_Egreso_Bienes_Proyecto_' . $parm] = $sv1;
		$_SESSION['so_Egreso_Bienes_Proyecto_' . $parm] = $so1;
		$_SESSION['sc_Egreso_Bienes_Proyecto_' . $parm] = $sc;
		$_SESSION['sv2_Egreso_Bienes_Proyecto_' . $parm] = $sv2;
		$_SESSION['so2_Egreso_Bienes_Proyecto_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWR_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $grFormError;

		// Initialize form error message
		$grFormError = "";

		// Check if validation required
		if (!EWR_SERVER_VALIDATE)
			return ($grFormError == "");

		// Return validate result
		$ValidateForm = ($grFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$grFormError .= ($grFormError <> "") ? "<p>&nbsp;</p>" : "";
			$grFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_Egreso_Bienes_Proyecto_$parm"] = "";
		$_SESSION["rf_Egreso_Bienes_Proyecto_$parm"] = "";
		$_SESSION["rt_Egreso_Bienes_Proyecto_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_Egreso_Bienes_Proyecto_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_Egreso_Bienes_Proyecto_$parm"];
		$fld->RangeTo = @$_SESSION["rt_Egreso_Bienes_Proyecto_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field proyecto
		$this->proyecto->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->proyecto->DropDownValue = $this->proyecto->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->proyecto, $sWrk, $this->proyecto->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->proyecto, $sWrk, $this->proyecto->DefaultSelectionList);
		if (!$this->SearchCommand) $this->proyecto->SelectionList = $this->proyecto->DefaultSelectionList;
		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/
		/**
		* Set up default values for popup filters
		*/

		// Field proyecto
		// $this->proyecto->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check proyecto extended filter
		if ($this->NonTextFilterApplied($this->proyecto))
			return TRUE;

		// Check proyecto popup filter
		if (!ewr_MatchedArray($this->proyecto->DefaultSelectionList, $this->proyecto->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field proyecto
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->proyecto, $sExtWrk, $this->proyecto->SearchOperator);
		if (is_array($this->proyecto->SelectionList))
			$sWrk = ewr_JoinArray($this->proyecto->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->proyecto->FldCaption() . "</span>" . $sFilter . "</div>";
		$divstyle = "";
		$divdataclass = "";

		// Show Filters
		if ($sFilterList <> "" || $showDate) {
			$sMessage = "<div" . $divstyle . $divdataclass . "><div id=\"ewrFilterList\" class=\"alert alert-info\">";
			if ($showDate)
				$sMessage .= "<div id=\"ewrCurrentDate\">" . $ReportLanguage->Phrase("ReportGeneratedDate") . ewr_FormatDateTime(date("Y-m-d H:i:s"), 1) . "</div>";
			if ($sFilterList <> "")
				$sMessage .= "<div id=\"ewrCurrentFilters\">" . $ReportLanguage->Phrase("CurrentFilters") . "</div>" . $sFilterList;
			$sMessage .= "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		// Field proyecto
		$sWrk = "";
		$sWrk = ($this->proyecto->DropDownValue <> EWR_INIT_VALUE) ? $this->proyecto->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_proyecto\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->proyecto->SelectionList <> EWR_INIT_VALUE) ? $this->proyecto->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_proyecto\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Return filter list in json
		if ($sFilterList <> "")
			return "{" . $sFilterList . "}";
		else
			return "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		return $this->SetupFilterList($filter);
	}

	// Setup list of filters
	function SetupFilterList($filter) {
		if (!is_array($filter))
			return FALSE;

		// Field proyecto
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_proyecto", $filter)) {
			$sWrk = $filter["sv_proyecto"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_proyecto"], "proyecto");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_proyecto", $filter)) {
			$sWrk = $filter["sel_proyecto"];
			$sWrk = explode("||", $sWrk);
			$this->proyecto->SelectionList = $sWrk;
			$_SESSION["sel_Egreso_Bienes_Proyecto_proyecto"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "proyecto"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "proyecto");
			$this->proyecto->SelectionList = "";
			$_SESSION["sel_Egreso_Bienes_Proyecto_proyecto"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->DropDownFilterExist($this->proyecto, $this->proyecto->SearchOperator)) {
			if (is_array($this->proyecto->SelectionList)) {
				$sFilter = ewr_FilterSql($this->proyecto, "`proyecto`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->proyecto, $sFilter, "popup");
				$this->proyecto->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		return $sWrk;
	}

	// Return drill down SQL
	// - fld = source field object
	// - target = target field name
	// - rowtype = row type
	//  * 0 = detail
	//  * 1 = group
	//  * 2 = page
	//  * 3 = grand
	// - parm = filter/column index
	//  * -1  = use field filter value / current/old value
	//  * 0   = use grouping/column field value
	//  * > 0 = use column index
	function GetDrillDownSQL($fld, $target, $rowtype, $parm = 0) {
		$sql = "";

		// Handle group/row/column field
		if ($parm >= 0 && $sql == "") {
			switch (substr($fld->FldVar,2)) {
			}
		}

		// Detail field
		if ($sql == "" && $rowtype == 0)
			if ($fld->CurrentFilter <> "") // Use current filter
				$sql = str_replace($fld->FldExpression, "@" . $target, $fld->CurrentFilter);
			elseif ($fld->CurrentValue <> "") // Use current value for detail row
				$sql = "@" . $target . "=" . ewr_QuotedValue($fld->CurrentValue, $fld->FldDataType, $this->DBID);
		return $sql;
	}

	// Get sort parameters based on sort links clicked
	function GetSort($options = array()) {
		if ($this->DrillDown)
			return "";
		$bResetSort = @$options["resetsort"] == "1" || @$_GET["cmd"] == "resetsort";
		$orderBy = (@$options["order"] <> "") ? @$options["order"] : @$_GET["order"];
		$orderType = (@$options["ordertype"] <> "") ? @$options["ordertype"] : @$_GET["ordertype"];

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
			$this->idegreso_bienes->setSort("");
			$this->numero_egreso->setSort("");
			$this->proyecto->setSort("");
			$this->fecha->setSort("");
			$this->lugar->setSort("");
			$this->calle->setSort("");
			$this->interseccion->setSort("");
			$this->descripcion->setSort("");
			$this->usuario->setSort("");
			$this->cajero->setSort("");
			$this->cedula_cajero->setSort("");
			$this->total->setSort("");
			$this->estado->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export to HTML
	function ExportHtml($html, $options = array()) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');

		$folder = @$this->GenOptions["folder"];
		$fileName = @$this->GenOptions["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";

		// Save generate file for print
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			$baseTag = "<base href=\"" . ewr_BaseUrl() . "\">";
			$html = preg_replace('/<head>/', '<head>' . $baseTag, $html);
			ewr_SaveFile($folder, $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file")
			echo $html;
		return $saveToFile;
	}

	// Export to WORD
	function ExportWord($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Set-Cookie: fileDownload=true; path=/');
			header('Content-Type: application/vnd.ms-word' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
			echo $html;
		}
		return $saveToFile;
	}

	// Export to EXCEL
	function ExportExcel($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Set-Cookie: fileDownload=true; path=/');
			header('Content-Type: application/vnd.ms-excel' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
			echo $html;
		}
		return $saveToFile;
	}

	// Export PDF
	function ExportPdf($html, $options = array()) {
		global $gsExportFile;
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		if (EWR_DEBUG_ENABLED) // Add debug message
			$html = str_replace("</body>", ewr_DebugMsg() . "</body>", $html);
		$dompdf = new \Dompdf\Dompdf(array("pdf_backend" => "Cpdf"));
		$doc = new DOMDocument();
		@$doc->loadHTML('<?xml encoding="uft-8">' . ewr_ConvertToUtf8($html)); // Convert to utf-8
		$spans = $doc->getElementsByTagName("span");
		foreach ($spans as $span) {
			if ($span->getAttribute("class") == "ewFilterCaption")
				$span->parentNode->insertBefore($doc->createElement("span", ":&nbsp;"), $span->nextSibling);
		}
		$images = $doc->getElementsByTagName("img");
		$pageSize = "a4";
		$pageOrientation = "portrait";
		foreach ($images as $image) {
			$imagefn = $image->getAttribute("src");
			if (file_exists($imagefn)) {
				$imagefn = realpath($imagefn);
				$size = getimagesize($imagefn); // Get image size
				if ($size[0] <> 0) {
					if (ewr_SameText($pageSize, "letter")) { // Letter paper (8.5 in. by 11 in.)
						$w = ewr_SameText($pageOrientation, "portrait") ? 216 : 279;
					} elseif (ewr_SameText($pageSize, "legal")) { // Legal paper (8.5 in. by 14 in.)
						$w = ewr_SameText($pageOrientation, "portrait") ? 216 : 356;
					} else {
						$w = ewr_SameText($pageOrientation, "portrait") ? 210 : 297; // A4 paper (210 mm by 297 mm)
					}
					$w = min($size[0], ($w - 20 * 2) / 25.4 * 72); // Resize image, adjust the multiplying factor if necessary
					$h = $w / $size[0] * $size[1];
					$image->setAttribute("width", $w);
					$image->setAttribute("height", $h);
				}
			}
		}
		$html = $doc->saveHTML();
		$html = ewr_ConvertFromUtf8($html);
		$dompdf->load_html($html);
		$dompdf->set_paper($pageSize, $pageOrientation);
		$dompdf->render();
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $dompdf->output());
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Set-Cookie: fileDownload=true; path=/');
			$sExportFile = strtolower(substr($gsExportFile, -4)) == ".pdf" ? $gsExportFile : $gsExportFile . ".pdf";
			$dompdf->stream($sExportFile, array("Attachment" => 1)); // 0 to open in browser, 1 to download
		}
		ewr_DeleteTmpImages($html);
		return $saveToFile;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php

// Create page object
if (!isset($Egreso_Bienes_Proyecto_summary)) $Egreso_Bienes_Proyecto_summary = new crEgreso_Bienes_Proyecto_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$Egreso_Bienes_Proyecto_summary;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();
if (!$grDashboardReport)
	ewr_Header(FALSE);

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php if (!$grDashboardReport) { ?>
<?php include_once "phprptinc/header.php" ?>
<?php } ?>
<?php if ($Page->Export == "" || $Page->Export == "print" || $Page->Export == "email" && @$gsEmailContentType == "url") { ?>
<script type="text/javascript">

// Create page object
var Egreso_Bienes_Proyecto_summary = new ewr_Page("Egreso_Bienes_Proyecto_summary");

// Page properties
Egreso_Bienes_Proyecto_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = Egreso_Bienes_Proyecto_summary.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fEgreso_Bienes_Proyectosummary = new ewr_Form("fEgreso_Bienes_Proyectosummary");

// Validate method
fEgreso_Bienes_Proyectosummary.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fEgreso_Bienes_Proyectosummary.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fEgreso_Bienes_Proyectosummary.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fEgreso_Bienes_Proyectosummary.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fEgreso_Bienes_Proyectosummary.Lists["sv_proyecto"] = {"LinkField":"sv_proyecto","Ajax":true,"DisplayFields":["sv_proyecto","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<a id="top"></a>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
<!-- Content Container -->
<div id="ewContainer" class="container-fluid ewContainer">
<?php } ?>
<?php if (@$Page->GenOptions["showfilter"] == "1") { ?>
<?php $Page->ShowFilterList(TRUE) ?>
<?php } ?>
<div class="ewToolbar">
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
	$Page->GenerateOptions->Render("body");
}
?>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
<div class="row">
<?php } ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
<!-- Center Container - Report -->
<div id="ewCenter" class="col-sm-12 ewCenter">
<?php } ?>
<!-- Summary Report begins -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_summary">
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<!-- Search form (begin) -->
<form name="fEgreso_Bienes_Proyectosummary" id="fEgreso_Bienes_Proyectosummary" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fEgreso_Bienes_Proyectosummary_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_proyecto" class="ewCell form-group">
	<label for="sv_proyecto" class="ewSearchCaption ewLabel"><?php echo $Page->proyecto->FldCaption() ?></label>
	<span class="ewSearchField">
<?php $Page->proyecto->EditAttrs["onclick"] = "ewrForms(this).Submit(); " . @$Page->proyecto->EditAttrs["onclick"]; ?>
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo ewr_FilterDropDownValue($Page->proyecto) ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_sv_proyecto" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
	$cntf = is_array($Page->proyecto->AdvancedFilters) ? count($Page->proyecto->AdvancedFilters) : 0;
	$cntd = is_array($Page->proyecto->DropDownList) ? count($Page->proyecto->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->proyecto->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->proyecto->DropDownValue, $filter->ID) ? " checked" : "";
?>
<input type="radio" data-table="Egreso_Bienes_Proyecto" data-field="x_proyecto" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->proyecto->DisplayValueSeparator) ? json_encode($Page->proyecto->DisplayValueSeparator) : $Page->proyecto->DisplayValueSeparator) ?>" data-filter-name="<?php echo ewr_HtmlEncode($filter->Name) ?>" name="sv_proyecto" value="<?php echo $filter->ID ?>"<?php echo $selwrk ?><?php echo $Page->proyecto->EditAttributes() ?>><?php echo $filter->Name ?>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " checked";
?>
<input type="radio" data-table="Egreso_Bienes_Proyecto" data-field="x_proyecto" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->proyecto->DisplayValueSeparator) ? json_encode($Page->proyecto->DisplayValueSeparator) : $Page->proyecto->DisplayValueSeparator) ?>" name="sv_proyecto" value="<?php echo $Page->proyecto->DropDownList[$i] ?>"<?php echo $selwrk ?><?php echo $Page->proyecto->EditAttributes() ?>><?php echo ewr_DropDownDisplayValue($Page->proyecto->DropDownList[$i], "", 0) ?>
<?php
		$wrkcnt += 1;
	}
?>
		</div>
	</div>
	<div id="tp_sv_proyecto" class="ewTemplate"><input type="radio" data-table="Egreso_Bienes_Proyecto" data-field="x_proyecto" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->proyecto->DisplayValueSeparator) ? json_encode($Page->proyecto->DisplayValueSeparator) : $Page->proyecto->DisplayValueSeparator) ?>" name="sv_proyecto" id="sv_proyecto" value="{value}"<?php echo $Page->proyecto->EditAttributes() ?>></div>
</div>
<input type="hidden" name="s_sv_proyecto" id="s_sv_proyecto" value="<?php echo $Page->proyecto->LookupFilterQuery() ?>">
<script type="text/javascript">
fEgreso_Bienes_Proyectosummary.Lists["sv_proyecto"].Options = <?php echo ewr_ArrayToJson($Page->proyecto->LookupFilterOptions) ?>;
</script>
</span>
</div>
</div>
</div>
</form>
<script type="text/javascript">
fEgreso_Bienes_Proyectosummary.Init();
fEgreso_Bienes_Proyectosummary.FilterList = <?php echo $Page->GetFilterList() ?>;
</script>
<!-- Search form (end) -->
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->ShowFilterList() ?>
<?php } ?>
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetRow(1);
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray(2, -1);
$Page->GrpIdx[0] = -1;
$Page->GrpIdx[1] = $Page->StopGrp - $Page->StartGrp + 1;
while ($rs && !$rs->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-header ewGridUpperPanel">
<?php include "Egreso_Bienes_Proyectosmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_Egreso_Bienes_Proyecto" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->idegreso_bienes->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="idegreso_bienes"><div class="Egreso_Bienes_Proyecto_idegreso_bienes"><span class="ewTableHeaderCaption"><?php echo $Page->idegreso_bienes->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="idegreso_bienes">
<?php if ($Page->SortUrl($Page->idegreso_bienes) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_idegreso_bienes">
			<span class="ewTableHeaderCaption"><?php echo $Page->idegreso_bienes->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_idegreso_bienes" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->idegreso_bienes) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->idegreso_bienes->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->idegreso_bienes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->idegreso_bienes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->numero_egreso->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="numero_egreso"><div class="Egreso_Bienes_Proyecto_numero_egreso"><span class="ewTableHeaderCaption"><?php echo $Page->numero_egreso->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="numero_egreso">
<?php if ($Page->SortUrl($Page->numero_egreso) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_numero_egreso">
			<span class="ewTableHeaderCaption"><?php echo $Page->numero_egreso->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_numero_egreso" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->numero_egreso) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->numero_egreso->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->numero_egreso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->numero_egreso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->proyecto->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="proyecto"><div class="Egreso_Bienes_Proyecto_proyecto"><span class="ewTableHeaderCaption"><?php echo $Page->proyecto->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="proyecto">
<?php if ($Page->SortUrl($Page->proyecto) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_proyecto">
			<span class="ewTableHeaderCaption"><?php echo $Page->proyecto->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'Egreso_Bienes_Proyecto_proyecto', range: true, from: '<?php echo $Page->proyecto->RangeFrom; ?>', to: '<?php echo $Page->proyecto->RangeTo; ?>', url: 'Egreso_Bienes_Proyectosmry.php' });" id="x_proyecto<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_proyecto" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->proyecto) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->proyecto->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->proyecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->proyecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'Egreso_Bienes_Proyecto_proyecto', range: true, from: '<?php echo $Page->proyecto->RangeFrom; ?>', to: '<?php echo $Page->proyecto->RangeTo; ?>', url: 'Egreso_Bienes_Proyectosmry.php' });" id="x_proyecto<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha"><div class="Egreso_Bienes_Proyecto_fecha"><span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha">
<?php if ($Page->SortUrl($Page->fecha) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_fecha">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_fecha" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->lugar->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="lugar"><div class="Egreso_Bienes_Proyecto_lugar"><span class="ewTableHeaderCaption"><?php echo $Page->lugar->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="lugar">
<?php if ($Page->SortUrl($Page->lugar) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_lugar">
			<span class="ewTableHeaderCaption"><?php echo $Page->lugar->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_lugar" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->lugar) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->lugar->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->lugar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->lugar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->calle->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="calle"><div class="Egreso_Bienes_Proyecto_calle"><span class="ewTableHeaderCaption"><?php echo $Page->calle->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="calle">
<?php if ($Page->SortUrl($Page->calle) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_calle">
			<span class="ewTableHeaderCaption"><?php echo $Page->calle->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_calle" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->calle) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->calle->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->calle->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->calle->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->interseccion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="interseccion"><div class="Egreso_Bienes_Proyecto_interseccion"><span class="ewTableHeaderCaption"><?php echo $Page->interseccion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="interseccion">
<?php if ($Page->SortUrl($Page->interseccion) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_interseccion">
			<span class="ewTableHeaderCaption"><?php echo $Page->interseccion->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_interseccion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->interseccion) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->interseccion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->interseccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->interseccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->descripcion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="descripcion"><div class="Egreso_Bienes_Proyecto_descripcion"><span class="ewTableHeaderCaption"><?php echo $Page->descripcion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="descripcion">
<?php if ($Page->SortUrl($Page->descripcion) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_descripcion">
			<span class="ewTableHeaderCaption"><?php echo $Page->descripcion->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_descripcion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->descripcion) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->descripcion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->usuario->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="usuario"><div class="Egreso_Bienes_Proyecto_usuario"><span class="ewTableHeaderCaption"><?php echo $Page->usuario->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="usuario">
<?php if ($Page->SortUrl($Page->usuario) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_usuario">
			<span class="ewTableHeaderCaption"><?php echo $Page->usuario->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_usuario" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->usuario) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->usuario->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cajero->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cajero"><div class="Egreso_Bienes_Proyecto_cajero"><span class="ewTableHeaderCaption"><?php echo $Page->cajero->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cajero">
<?php if ($Page->SortUrl($Page->cajero) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_cajero">
			<span class="ewTableHeaderCaption"><?php echo $Page->cajero->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_cajero" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cajero) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cajero->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cajero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cajero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->cedula_cajero->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="cedula_cajero"><div class="Egreso_Bienes_Proyecto_cedula_cajero"><span class="ewTableHeaderCaption"><?php echo $Page->cedula_cajero->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="cedula_cajero">
<?php if ($Page->SortUrl($Page->cedula_cajero) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_cedula_cajero">
			<span class="ewTableHeaderCaption"><?php echo $Page->cedula_cajero->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_cedula_cajero" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->cedula_cajero) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->cedula_cajero->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->cedula_cajero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->cedula_cajero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="total"><div class="Egreso_Bienes_Proyecto_total"><span class="ewTableHeaderCaption"><?php echo $Page->total->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="total">
<?php if ($Page->SortUrl($Page->total) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_total">
			<span class="ewTableHeaderCaption"><?php echo $Page->total->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_total" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->total) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->total->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->estado->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="estado"><div class="Egreso_Bienes_Proyecto_estado"><span class="ewTableHeaderCaption"><?php echo $Page->estado->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="estado">
<?php if ($Page->SortUrl($Page->estado) == "") { ?>
		<div class="ewTableHeaderBtn Egreso_Bienes_Proyecto_estado">
			<span class="ewTableHeaderCaption"><?php echo $Page->estado->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Egreso_Bienes_Proyecto_estado" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->estado) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->estado->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}
	$Page->RecCount++;
	$Page->RecIndex++;
?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->idegreso_bienes->Visible) { ?>
		<td data-field="idegreso_bienes"<?php echo $Page->idegreso_bienes->CellAttributes() ?>>
<span<?php echo $Page->idegreso_bienes->ViewAttributes() ?>><?php echo $Page->idegreso_bienes->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->numero_egreso->Visible) { ?>
		<td data-field="numero_egreso"<?php echo $Page->numero_egreso->CellAttributes() ?>>
<span<?php echo $Page->numero_egreso->ViewAttributes() ?>><?php echo $Page->numero_egreso->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->proyecto->Visible) { ?>
		<td data-field="proyecto"<?php echo $Page->proyecto->CellAttributes() ?>>
<span<?php echo $Page->proyecto->ViewAttributes() ?>>
<?php if ($Page->proyecto->HrefValue <> "" || @$Page->proyecto->LinkAttrs["onclick"] <> "") { ?>
<?php if ($Page->proyecto->ListViewValue() <> "" && $Page->proyecto->ListViewValue() <> "&nbsp;") { ?>
<a<?php echo $Page->proyecto->LinkAttributes() ?>><?php echo $Page->proyecto->ListViewValue() ?></a>
<?php } else { echo "&nbsp;"; } ?>
<?php } else { ?>
<?php if ($Page->proyecto->ListViewValue() <> "" && $Page->proyecto->ListViewValue() <> "&nbsp;") { ?>
<?php echo $Page->proyecto->ListViewValue() ?>
<?php } else { echo "&nbsp;"; } ?>
<?php } ?>
</span></td>
<?php } ?>
<?php if ($Page->fecha->Visible) { ?>
		<td data-field="fecha"<?php echo $Page->fecha->CellAttributes() ?>>
<span<?php echo $Page->fecha->ViewAttributes() ?>><?php echo $Page->fecha->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->lugar->Visible) { ?>
		<td data-field="lugar"<?php echo $Page->lugar->CellAttributes() ?>>
<span<?php echo $Page->lugar->ViewAttributes() ?>><?php echo $Page->lugar->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->calle->Visible) { ?>
		<td data-field="calle"<?php echo $Page->calle->CellAttributes() ?>>
<span<?php echo $Page->calle->ViewAttributes() ?>><?php echo $Page->calle->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->interseccion->Visible) { ?>
		<td data-field="interseccion"<?php echo $Page->interseccion->CellAttributes() ?>>
<span<?php echo $Page->interseccion->ViewAttributes() ?>><?php echo $Page->interseccion->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { ?>
		<td data-field="descripcion"<?php echo $Page->descripcion->CellAttributes() ?>>
<span<?php echo $Page->descripcion->ViewAttributes() ?>><?php echo $Page->descripcion->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->usuario->Visible) { ?>
		<td data-field="usuario"<?php echo $Page->usuario->CellAttributes() ?>>
<span<?php echo $Page->usuario->ViewAttributes() ?>><?php echo $Page->usuario->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cajero->Visible) { ?>
		<td data-field="cajero"<?php echo $Page->cajero->CellAttributes() ?>>
<span<?php echo $Page->cajero->ViewAttributes() ?>><?php echo $Page->cajero->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->cedula_cajero->Visible) { ?>
		<td data-field="cedula_cajero"<?php echo $Page->cedula_cajero->CellAttributes() ?>>
<span<?php echo $Page->cedula_cajero->ViewAttributes() ?>><?php echo $Page->cedula_cajero->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
		<td data-field="total"<?php echo $Page->total->CellAttributes() ?>>
<span<?php echo $Page->total->ViewAttributes() ?>><?php echo $Page->total->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->estado->Visible) { ?>
		<td data-field="estado"<?php echo $Page->estado->CellAttributes() ?>>
<span<?php echo $Page->estado->ViewAttributes() ?>><?php echo $Page->estado->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);
	$Page->GrpCount++;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php
	$Page->total->Count = $Page->GrandCnt[12];
	$Page->total->SumValue = $Page->GrandSmry[12]; // Load SUM
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
<?php if ($Page->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> (<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2) ?></span>)</td></tr>
	<tr<?php echo $Page->RowAttributes() ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate">&nbsp;</td>
<?php } ?>
<?php if ($Page->idegreso_bienes->Visible) { ?>
		<td data-field="idegreso_bienes"<?php echo $Page->idegreso_bienes->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->numero_egreso->Visible) { ?>
		<td data-field="numero_egreso"<?php echo $Page->numero_egreso->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->proyecto->Visible) { ?>
		<td data-field="proyecto"<?php echo $Page->proyecto->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->fecha->Visible) { ?>
		<td data-field="fecha"<?php echo $Page->fecha->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->lugar->Visible) { ?>
		<td data-field="lugar"<?php echo $Page->lugar->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->calle->Visible) { ?>
		<td data-field="calle"<?php echo $Page->calle->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->interseccion->Visible) { ?>
		<td data-field="interseccion"<?php echo $Page->interseccion->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { ?>
		<td data-field="descripcion"<?php echo $Page->descripcion->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->usuario->Visible) { ?>
		<td data-field="usuario"<?php echo $Page->usuario->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->cajero->Visible) { ?>
		<td data-field="cajero"<?php echo $Page->cajero->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->cedula_cajero->Visible) { ?>
		<td data-field="cedula_cajero"<?php echo $Page->cedula_cajero->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
		<td data-field="total"<?php echo $Page->total->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span<?php echo $Page->total->ViewAttributes() ?>><?php echo $Page->total->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->estado->Visible) { ?>
		<td data-field="estado"<?php echo $Page->estado->CellAttributes() ?>></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
	<tr<?php echo $Page->RowAttributes() ?>>
<?php if ($Page->idegreso_bienes->Visible) { ?>
		<td data-field="idegreso_bienes"<?php echo $Page->idegreso_bienes->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->numero_egreso->Visible) { ?>
		<td data-field="numero_egreso"<?php echo $Page->numero_egreso->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->proyecto->Visible) { ?>
		<td data-field="proyecto"<?php echo $Page->proyecto->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { ?>
		<td data-field="fecha"<?php echo $Page->fecha->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->lugar->Visible) { ?>
		<td data-field="lugar"<?php echo $Page->lugar->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->calle->Visible) { ?>
		<td data-field="calle"<?php echo $Page->calle->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->interseccion->Visible) { ?>
		<td data-field="interseccion"<?php echo $Page->interseccion->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { ?>
		<td data-field="descripcion"<?php echo $Page->descripcion->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->usuario->Visible) { ?>
		<td data-field="usuario"<?php echo $Page->usuario->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->cajero->Visible) { ?>
		<td data-field="cajero"<?php echo $Page->cajero->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->cedula_cajero->Visible) { ?>
		<td data-field="cedula_cajero"<?php echo $Page->cedula_cajero->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
		<td data-field="total"<?php echo $Page->total->CellAttributes() ?>><span class="ewAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateColon") ?>
<span<?php echo $Page->total->ViewAttributes() ?>><?php echo $Page->total->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->estado->Visible) { ?>
		<td data-field="estado"<?php echo $Page->estado->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
	</tr>
<?php } ?>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && TRUE) { // No header displayed ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-header ewGridUpperPanel">
<?php include "Egreso_Bienes_Proyectosmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_Egreso_Bienes_Proyecto" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || TRUE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<!-- Summary Report Ends -->
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
</div>
<!-- /#ewCenter -->
<?php } ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
</div>
<!-- /.row -->
<?php } ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
</div>
<!-- /.ewContainer -->
<?php } ?>
<?php
$Page->ShowPageFooter();
if (EWR_DEBUG_ENABLED)
	echo ewr_DebugMsg();
?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// console.log("page loaded");

</script>
<?php } ?>
<?php if (!$grDashboardReport) { ?>
<?php include_once "phprptinc/footer.php" ?>
<?php } ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
