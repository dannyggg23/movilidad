<?php

// Global variable for table object
$Egreso_Bienes = NULL;

//
// Table class for Egreso-Bienes
//
class crEgreso_Bienes extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $idegreso_bienes;
	var $numero_egreso;
	var $fecha;
	var $usuario;
	var $nombre;
	var $descripcion;
	var $lugar;
	var $calle;
	var $interseccion;
	var $total;
	var $estado;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'Egreso_Bienes';
		$this->TableName = 'Egreso-Bienes';
		$this->TableType = 'REPORT';
		$this->TableReportType = 'summary';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// idegreso_bienes
		$this->idegreso_bienes = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_idegreso_bienes', 'idegreso_bienes', '`idegreso_bienes`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->idegreso_bienes->Sortable = TRUE; // Allow sort
		$this->idegreso_bienes->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->idegreso_bienes->DateFilter = "";
		$this->idegreso_bienes->SqlSelect = "";
		$this->idegreso_bienes->SqlOrderBy = "";
		$this->idegreso_bienes->DrillDownUrl = "r_detalle_egreso_bienesrpt.php?d=1&t=r_detalle_egreso_bienes&s=Egreso_Bienes&egreso_bienes_idegreso_bienes=f0";
		$this->fields['idegreso_bienes'] = &$this->idegreso_bienes;

		// numero_egreso
		$this->numero_egreso = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_numero_egreso', 'numero_egreso', '`numero_egreso`', 200, EWR_DATATYPE_STRING, -1);
		$this->numero_egreso->Sortable = TRUE; // Allow sort
		$this->numero_egreso->DateFilter = "";
		$this->numero_egreso->SqlSelect = "";
		$this->numero_egreso->SqlOrderBy = "";
		$this->fields['numero_egreso'] = &$this->numero_egreso;

		// fecha
		$this->fecha = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_fecha', 'fecha', '`fecha`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha->Sortable = TRUE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha->DateFilter = "";
		$this->fecha->SqlSelect = "SELECT DISTINCT `fecha`, `fecha` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->fecha->SqlOrderBy = "`fecha`";
		$this->fields['fecha'] = &$this->fecha;

		// usuario
		$this->usuario = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_usuario', 'usuario', '`usuario`', 200, EWR_DATATYPE_STRING, -1);
		$this->usuario->Sortable = TRUE; // Allow sort
		$this->usuario->DateFilter = "";
		$this->usuario->SqlSelect = "";
		$this->usuario->SqlOrderBy = "";
		$this->fields['usuario'] = &$this->usuario;

		// nombre
		$this->nombre = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_nombre', 'nombre', '`nombre`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->nombre->DateFilter = "";
		$this->nombre->SqlSelect = "";
		$this->nombre->SqlOrderBy = "";
		$this->fields['nombre'] = &$this->nombre;

		// descripcion
		$this->descripcion = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_descripcion', 'descripcion', '`descripcion`', 201, EWR_DATATYPE_MEMO, -1);
		$this->descripcion->Sortable = TRUE; // Allow sort
		$this->descripcion->DateFilter = "";
		$this->descripcion->SqlSelect = "";
		$this->descripcion->SqlOrderBy = "";
		$this->fields['descripcion'] = &$this->descripcion;

		// lugar
		$this->lugar = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_lugar', 'lugar', '`lugar`', 200, EWR_DATATYPE_STRING, -1);
		$this->lugar->Sortable = TRUE; // Allow sort
		$this->lugar->DateFilter = "";
		$this->lugar->SqlSelect = "";
		$this->lugar->SqlOrderBy = "";
		$this->fields['lugar'] = &$this->lugar;

		// calle
		$this->calle = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_calle', 'calle', '`calle`', 200, EWR_DATATYPE_STRING, -1);
		$this->calle->Sortable = TRUE; // Allow sort
		$this->calle->DateFilter = "";
		$this->calle->SqlSelect = "";
		$this->calle->SqlOrderBy = "";
		$this->fields['calle'] = &$this->calle;

		// interseccion
		$this->interseccion = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_interseccion', 'interseccion', '`interseccion`', 200, EWR_DATATYPE_STRING, -1);
		$this->interseccion->Sortable = TRUE; // Allow sort
		$this->interseccion->DateFilter = "";
		$this->interseccion->SqlSelect = "";
		$this->interseccion->SqlOrderBy = "";
		$this->fields['interseccion'] = &$this->interseccion;

		// total
		$this->total = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_total', 'total', '`total`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->total->Sortable = TRUE; // Allow sort
		$this->total->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->total->DateFilter = "";
		$this->total->SqlSelect = "";
		$this->total->SqlOrderBy = "";
		$this->fields['total'] = &$this->total;

		// estado
		$this->estado = new crField('Egreso_Bienes', 'Egreso-Bienes', 'x_estado', 'estado', '`estado`', 200, EWR_DATATYPE_STRING, -1);
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`r_egreso_bienes`";
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
