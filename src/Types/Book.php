<?php namespace Piestar\CitationBuilder\Types;

class Book extends Work {

	protected $requiredFields = [
		'title',
		'authors',
	];

}