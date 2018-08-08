<?php

// Menu
$RootMenu = new crMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(20, "mi_Bienes_Categorias", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("20", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Bienes_Categoriassmry.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(21, "mi_Especies_Categorias", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("21", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Especies_Categoriassmry.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(23, "mi_Ingreso_Bienes", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("23", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Ingreso_Bienessmry.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(27, "mi_Egreso_Bienes", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("27", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Egreso_Bienessmry.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(30, "mi_Ingreso_Especies", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("30", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Ingreso_Especiessmry.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(33, "mi_Egreso_Especies", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("33", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Egreso_Especiessmry.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(36, "mi_Egreso_Bienes_Proyecto", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("36", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Egreso_Bienes_Proyectosmry.php", -1, "", TRUE, FALSE, FALSE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
