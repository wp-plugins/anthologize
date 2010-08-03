<?php
// TeiPdf - Generates PDF from internal, hybridized TEI.
//
// This file is part of Anthologize
//
// Written and maintained by Stephen Ramsay <sramsay.unl@gmail.com>
//
// Last Modified: Sat Jul 31 08:14:13 EDT 2010
//
// Copyright (c) 2010 Center for History and New Media, George Mason
// University.
//
// TeiPdf is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 3, or (at your option) any
// later version.
//
// TeiPdf is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
// or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License
// for more details.
//
// You should have received a copy of the GNU General Public License
// along with TeiPdf; see the file COPYING.  If not see
// <http://www.gnu.org/licenses/>.

include_once(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'anthologize' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'eng.php');
include_once(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'anthologize' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php');
include_once(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'anthologize' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-tei.php');
include_once(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'anthologize' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'pdf-html-filter.php');

define('TEI', 'http://www.tei-c.org/ns/1.0');
define('HTML', 'http://www.w3.org/1999/xhtml');
define('ANTH', 'http://www.anthologize.org/ns');

class TeiPdf {

	public $tei;
	public $pdf;
	public $xpath;

	function __construct($tei_master) {

		$this->tei = $tei_master;

		$paper_size = $this->tei->get_paper_size();

		$this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $paper_size, true, 'UTF-8', false);

// -------------------------------------------------------- //

		//set auto page breaks
		$this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		//set image scale factor
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$this->pdf->setLanguageArray($l);

		$this->set_docinfo();
		$this->set_font();
		$this->set_margins();

	}

	public function write_pdf() {

		$this->pdf->AddPage();

		$book_title = $this->tei->get_book_title();
		
		// Create a nodeList containing all parts.
		$parts = $this->tei->get_parts();

		foreach ($parts as $part) {
			// Grab the main title for each part and render it as
			// a "chapter" title.
			$title = $this->tei->get_title($part);

			$html = $html . "<h1>" . $title . "</h1>";

			// Create a nodeList containing all libraryItems
			$library_items = $this->tei->get_div("libraryItem", $part);

			foreach ($library_items as $item) {

				// Grab the main title for each libraryItem and render it
				// as a "sub section" title.
				$sub_title = $this->tei->get_title($item);
				
				$html = $html . "<h3>" . $sub_title . "</h3>";

				// All content below <html:body>
				$post_content = $this->tei->get_html($item);
				$post_conent  = filter_html($post_content);

				$html = $html . $post_content;

			} // foreach item

		} // foreach part

		$this->pdf->WriteHTML($html, true, 0, true, 0);

		// Close and output PDF document
		// This method has several options, check the source code
		// documentation for more information.

		//echo get_class($html); // DEBUG
		$filename = $book_title . ".pdf";
		$this->pdf->Output($filename, 'I');

	} // writePDF 

	public function set_header() {

		// set default header data
		$this->pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING);

	}

	public function set_footer() {

		// set header and footer fonts
		$this->pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	}

	public function set_docinfo() {

		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('One Week | One Tool');
		$this->pdf->SetTitle('An Amazing Example of PDF Generation');
		$this->pdf->SetSubject('Barbecue');
		$this->pdf->SetKeywords('Boone, barbecue, oneweek, pants');

	}

	public function set_font() {

		// set default monospaced font
		$this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set default font subsetting mode
		$this->pdf->setFontSubsetting(true);

		$font_family = $this->tei->get_font_family();
		$font_size   = $this->tei->get_font_size();

		$this->pdf->SetFont($font_family, '', $font_size, '', true);

	}

	public function set_margins() {

		$this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	}



} // TeiPdf

// -------------------------------------------------------- //

?>
