<?php

// Global variable for table object
$Bienes_Categorias = NULL;

//
// Table class for Bienes-Categorias
//
class crBienes_Categorias extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $idbienes;
	var $codigo;
	var $nombre;
	var $descripcion;
	var $tipo;
	var $imagen;
	var $stock;
	var $valor;
	var $cateroria;
	var $condicion;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'Bienes_Categorias';
		$this->TableName = 'Bienes-Categorias';
		$this->TableType = 'REPORT';
		$this->TableReportType = 'summary';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)

		// idbienes
		$this->idbienes = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_idbienes', 'idbienes', '`idbienes`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->idbienes->Sortable = TRUE; // Allow sort
		$this->idbienes->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->idbienes->DateFilter = "";
		$this->idbienes->SqlSelect = "";
		$this->idbienes->SqlOrderBy = "";
		$this->fields['idbienes'] = &$this->idbienes;

		// codigo
		$this->codigo = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_codigo', 'codigo', '`codigo`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->codigo->Sortable = TRUE; // Allow sort
		$this->codigo->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->codigo->DateFilter = "";
		$this->codigo->SqlSelect = "";
		$this->codigo->SqlOrderBy = "";
		$this->fields['codigo'] = &$this->codigo;

		// nombre
		$this->nombre = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_nombre', 'nombre', '`nombre`', 200, EWR_DATATYPE_STRING, -1);
		$this->nombre->Sortable = TRUE; // Allow sort
		$this->nombre->DateFilter = "";
		$this->nombre->SqlSelect = "";
		$this->nombre->SqlOrderBy = "";
		$this->fields['nombre'] = &$this->nombre;

		// descripcion
		$this->descripcion = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_descripcion', 'descripcion', '`descripcion`', 200, EWR_DATATYPE_STRING, -1);
		$this->descripcion->Sortable = TRUE; // Allow sort
		$this->descripcion->DateFilter = "";
		$this->descripcion->SqlSelect = "";
		$this->descripcion->SqlOrderBy = "";
		$this->fields['descripcion'] = &$this->descripcion;

		// tipo
		$this->tipo = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_tipo', 'tipo', '`tipo`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipo->Sortable = TRUE; // Allow sort
		$this->tipo->DateFilter = "";
		$this->tipo->SqlSelect = "";
		$this->tipo->SqlOrderBy = "";
		$this->fields['tipo'] = &$this->tipo;

		// imagen
		$this->imagen = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_imagen', 'imagen', '`imagen`', 200, EWR_DATATYPE_STRING, -1);
		$this->imagen->Sortable = TRUE; // Allow sort
		$this->imagen->DateFilter = "";
		$this->imagen->SqlSelect = "";
		$this->imagen->SqlOrderBy = "";
		$this->fields['imagen'] = &$this->imagen;

		// stock
		$this->stock = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_stock', 'stock', '`stock`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->stock->Sortable = TRUE; // Allow sort
		$this->stock->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->stock->DateFilter = "";
		$this->stock->SqlSelect = "";
		$this->stock->SqlOrderBy = "";
		$this->fields['stock'] = &$this->stock;

		// valor
		$this->valor = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_valor', 'valor', '`valor`', 131, EWR_DATATYPE_NUMBER, -1);
		$this->valor->Sortable = TRUE; // Allow sort
		$this->valor->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->valor->DateFilter = "";
		$this->valor->SqlSelect = "";
		$this->valor->SqlOrderBy = "";
		$this->fields['valor'] = &$this->valor;

		// cateroria
		$this->cateroria = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_cateroria', 'cateroria', '`cateroria`', 200, EWR_DATATYPE_STRING, -1);
		$this->cateroria->Sortable = TRUE; // Allow sort
		$this->cateroria->DateFilter = "";
		$this->cateroria->SqlSelect = "SELECT DISTINCT `cateroria`, `cateroria` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->cateroria->SqlOrderBy = "`cateroria`";
		$this->fields['cateroria'] = &$this->cateroria;

		// condicion
		$this->condicion = new crField('Bienes_Categorias', 'Bienes-Categorias', 'x_condicion', 'condicion', '`condicion`', 16, EWR_DATATYPE_NUMBER, -1);
		$this->condicion->Sortable = TRUE; // Allow sort
		$this->condicion->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->condicion->DateFilter = "";
		$this->condicion->SqlSelect = "";
		$this->condicion->SqlOrderBy = "";
		$this->fields['condicion'] = &$this->condicion;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`bienes_por_categorias`";
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
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT * FROM " . $this->getSqlFrom();
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

	// Get record count
	public function getRecordCount($sql)
	{
		$cnt = -1;
		$rs = NULL;
		$sql = preg_replace('/\/\*BeginOrderBy\*\/[\s\S]+\/\*EndOrderBy\*\//', "", $sql); // Remove ORDER BY clause (MSSQL)
		$pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';

		// Skip Custom View / SubQuery and SELECT DISTINCT
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
			preg_match($pattern, $sql) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sql) && !preg_match('/^\s*select\s+distinct\s+/i', $sql)) {
			$sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sql);
		} else {
			$sqlwrk = "SELECT COUNT(*) FROM (" . $sql . ") COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->execute($sqlwrk)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->close();
			}
			return (int)$cnt;
		}

		// Unable to get count, get record count directly
		if ($rs = $conn->execute($sql)) {
			$cnt = $rs->RecordCount();
			$rs->close();
			return (int)$cnt;
		}
		return $cnt;
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
		case "x_cateroria":
			$fld->LookupFilters = array("d" => "DB", "f0" => '`cateroria` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter), "af" => json_encode($fld->AdvancedFilters));
		$sWhereWrk = "";
		$fld->LookupFilters += array(
			"select" => "SELECT DISTINCT `cateroria`, `cateroria` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `bienes_por_categorias`",
			"where" => $sWhereWrk,
			"orderby" => "`cateroria` ASC"
		);
		$this->Lookup_Selecting($fld, $fld->LookupFilters["where"]); // Call Lookup selecting
		$fld->LookupFilters["s"] = ewr_BuildReportSql($fld->LookupFilters["select"], $fld->LookupFilters["where"], "", "", $fld->LookupFilters["orderby"], "", "");
			break;
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
