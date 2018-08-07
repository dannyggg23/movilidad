<?php

// Global variable for table object
$Ingreso_Bienes = NULL;

//
// Table class for Ingreso-Bienes
//
class crIngreso_Bienes extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $idingreso_bienes;
	var $numero_ingreso;
	var $nombre;
	var $fecha;
	var $ubicacion;
	var $detalle;
	var $total;
	var $n_documento;
	var $estado;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'Ingreso_Bienes';
		$this->TableName = 'Ingreso-Bienes';
		$this->TableType = 'REPORT';
		$this->TableReportType = 'summary';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// idingreso_bienes
		$this->idingreso_bienes = new crField('Ingreso_Bienes', 'Ingreso-Bienes', 'x_idingreso_bienes', 'idingreso_bienes', '`idingreso_bienes`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->idingreso_bienes->Sortable = TRUE; // Allow sort
		$this->idingreso_bienes->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->idingreso_bienes->DateFilter = "";
		$this->idingreso_bienes->SqlSelect = "";
		$this->idingreso_bienes->SqlOrderBy = "";
		$this->idingreso_bienes->DrillDownUrl = "r_detalle_ingreso_bienesrpt.php?d=1&t=r_detalle_ingreso_bienes&s=Ingreso_Bienes&ingreso_bienes_idingreso_bienes=f0";
		$this->fields['idingreso_bienes'] = &$this->idingreso_bienes;

		// numero_ingreso
		$this->numero_ingreso = new crField('Ingreso_Bienes', 'Ingreso-Bienes', 'x_numero_ingreso', 'numero_ingreso', '`numero_ingreso`', 200, EWR_DATATYPE_STRING, -1);
		$this->numero_ingreso->Sortable = TRUE; // Allow sort
		$this->numero_ingreso->DateFilter = "";
		$this->numero_ingreso->SqlSelect = "";
		$this->numero_ingreso->SqlOrderBy = "";
		$this->fields['numero_ingreso'] = &$this->numero_ingreso;

		// nombre
		$this->nombre = new crField('Ingreso_Bienes', 'Ingreso-Bienes', 'x_nombre', 'nombre', '`nombre`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->nombre->DateFilter = "";
		$this->nombre->SqlSelect = "";
		$this->nombre->SqlOrderBy = "";
		$this->fields['nombre'] = &$this->nombre;

		// fecha
		$this->fecha = new crField('Ingreso_Bienes', 'Ingreso-Bienes', 'x_fecha', 'fecha', '`fecha`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha->Sortable = TRUE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha->DateFilter = "";
		$this->fecha->SqlSelect = "SELECT DISTINCT `fecha`, `fecha` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fecha->SqlOrderBy = "`fecha`";
		$this->fields['fecha'] = &$this->fecha;

		// ubicacion
		$this->ubicacion = new crField('Ingreso_Bienes', 'Ingreso-Bienes', 'x_ubicacion', 'ubicacion', '`ubicacion`', 200, EWR_DATATYPE_STRING, -1);
		$this->ubicacion->Sortable = TRUE; // Allow sort
		$this->ubicacion->DateFilter = "";
		$this->ubicacion->SqlSelect = "";
		$this->ubicacion->SqlOrderBy = "";
		$this->fields['ubicacion'] = &$this->ubicacion;

		// detalle
		$this->detalle = new crField('Ingreso_Bienes', 'Ingreso-Bienes', 'x_detalle', 'detalle', '`detalle`', 200, EWR_DATATYPE_STRING, -1);
		$this->detalle->Sortable = TRUE; // Allow sort
		$this->detalle->DateFilter = "";
		$this->detalle->SqlSelect = "";
		$this->detalle->SqlOrderBy = "";
		$this->fields['detalle'] = &$this->detalle;

		// total
		$this->total = new crField('Ingreso_Bienes', 'Ingreso-Bienes', 'x_total', 'total', '`total`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->total->Sortable = TRUE; // Allow sort
		$this->total->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->total->DateFilter = "";
		$this->total->SqlSelect = "";
		$this->total->SqlOrderBy = "";
		$this->fields['total'] = &$this->total;

		// n_documento
		$this->n_documento = new crField('Ingreso_Bienes', 'Ingreso-Bienes', 'x_n_documento', 'n_documento', '`n_documento`', 200, EWR_DATATYPE_STRING, -1);
		$this->n_documento->Sortable = TRUE; // Allow sort
		$this->n_documento->DateFilter = "";
		$this->n_documento->SqlSelect = "";
		$this->n_documento->SqlOrderBy = "";
		$this->fields['n_documento'] = &$this->n_documento;

		// estado
		$this->estado = new crField('Ingreso_Bienes', 'Ingreso-Bienes', 'x_estado', 'estado', '`estado`', 200, EWR_DATATYPE_STRING, -1);
		$this->estado->Sortable = TRUE; // Allow sort
		$this->estado->DateFilter = "";
		$this->estado->SqlSelect = "";
		$this->estado->SqlOrderBy = "";
		$this->fields['estado'] = &$this->estado;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ofld->GroupingFieldId == 0)
				$this->setDetailOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			if ($ofld->GroupingFieldId == 0) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				$fldsql = $fld->FldExpression;
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	// From

	var $_SqlFrom = "";

	function getSqlFrom() {
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`r_ingreso_bienes`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}

	// Select
	var $_SqlSelect = "";

	function getSqlSelect() {
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}

	// Where
	var $_SqlWhere = "";

	function getSqlWhere() {
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}

	// Group By
	var $_SqlGroupBy = "";

	function getSqlGroupBy() {
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}

	// Having
	var $_SqlHaving = "";

	function getSqlHaving() {
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}

	// Order By
	var $_SqlOrderBy = "";

	function getSqlOrderBy() {
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Table Level Group SQL
	// First Group Field

	var $_SqlFirstGroupField = "";

	function getSqlFirstGroupField() {
		return ($this->_SqlFirstGroupField <> "") ? $this->_SqlFirstGroupField : "";
	}

	function SqlFirstGroupField() { // For backward compatibility
		return $this->getSqlFirstGroupField();
	}

	function setSqlFirstGroupField($v) {
		$this->_SqlFirstGroupField = $v;
	}

	// Select Group
	var $_SqlSelectGroup = "";

	function getSqlSelectGroup() {
		return ($this->_SqlSelectGroup <> "") ? $this->_SqlSelectGroup : "SELECT DISTINCT " . $this->getSqlFirstGroupField() . " FROM " . $this->getSqlFrom();
	}

	function SqlSelectGroup() { // For backward compatibility
		return $this->getSqlSelectGroup();
	}

	function setSqlSelectGroup($v) {
		$this->_SqlSelectGroup = $v;
	}

	// Order By Group
	var $_SqlOrderByGroup = "";

	function getSqlOrderByGroup() {
		return ($this->_SqlOrderByGroup <> "") ? $this->_SqlOrderByGroup : "";
	}

	function SqlOrderByGroup() { // For backward compatibility
		return $this->getSqlOrderByGroup();
	}

	function setSqlOrderByGroup($v) {
		$this->_SqlOrderByGroup = $v;
	}

	// Select Aggregate
	var $_SqlSelectAgg = "";

	function getSqlSelectAgg() {
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT SUM(`total`) AS `sum_total` FROM " . $this->getSqlFrom();
	}

	function SqlSelectAgg() { // For backward compatibility
		return $this->getSqlSelectAgg();
	}

	function setSqlSelectAgg($v) {
		$this->_SqlSelectAgg = $v;
	}

	// Aggregate Prefix
	var $_SqlAggPfx = "";

	function getSqlAggPfx() {
		return ($this->_SqlAggPfx <> "") ? $this->_SqlAggPfx : "";
	}

	function SqlAggPfx() { // For backward compatibility
		return $this->getSqlAggPfx();
	}

	function setSqlAggPfx($v) {
		$this->_SqlAggPfx = $v;
	}

	// Aggregate Suffix
	var $_SqlAggSfx = "";

	function getSqlAggSfx() {
		return ($this->_SqlAggSfx <> "") ? $this->_SqlAggSfx : "";
	}

	function SqlAggSfx() { // For backward compatibility
		return $this->getSqlAggSfx();
	}

	function setSqlAggSfx($v) {
		$this->_SqlAggSfx = $v;
	}

	// Select Count
	var $_SqlSelectCount = "";

	function getSqlSelectCount() {
		return ($this->_SqlSelectCount <> "") ? $this->_SqlSelectCount : "SELECT COUNT(*) FROM " . $this->getSqlFrom();
	}

	function SqlSelectCount() { // For backward compatibility
		return $this->getSqlSelectCount();
	}

	function setSqlSelectCount($v) {
		$this->_SqlSelectCount = $v;
	}

	// Sort URL
	function SortUrl(&$fld) {
		global $grDashboardReport;
		return "";
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld) {
		global $grLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld) {
		global $grLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Table level events
	// Page Selecting event
	function Page_Selecting(&$filter) {

		// Enter your code here
	}

	// Page Breaking event
	function Page_Breaking(&$break, &$content) {

		// Example:
		//$break = FALSE; // Skip page break, or
		//$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}

	// Load Filters event
	function Page_FilterLoad() {

		// Enter your code here
		// Example: Register/Unregister Custom Extended Filter
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
		//ewr_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//$this->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Page Filtering event
	function Page_Filtering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "") {

		// Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
		//if ($typ == "dropdown" && $fld->FldName == "MyField") // Dropdown filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "extended" && $fld->FldName == "MyField") // Extended filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "popup" && $fld->FldName == "MyField") // Popup filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "custom" && $opr == "..." && $fld->FldName == "MyField") // Custom filter, $opr is the custom filter ID
		//	$filter = "..."; // Modify the filter

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}
}
?>
