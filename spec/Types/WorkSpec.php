<?php  namespace spec\Piestar\CitationBuilder\Types;

use PhpSpec\ObjectBehavior;
use Piestar\CitationBuilder\Exceptions\MissingRequiredFieldsException;
use Piestar\CitationBuilder\Exceptions\UnknownWorkTypeException;
use Piestar\CitationBuilder\Styles\Apa6;
use Piestar\CitationBuilder\Styles\Mla7;
use Piestar\CitationBuilder\Types\Book;
use Piestar\CitationBuilder\Types\Work;
use Prophecy\Argument;

class WorkSpec extends ObjectBehavior
{
	function it_is_initializable()
	{
		$this->beConstructedThrough('factory', [['type' => 'book', 'title' => 'foo', 'authors' => ['Ben']]]);
		$this->getWrappedObject();
		$this->shouldHaveType(Book::class);
	}

	function it_throws_an_exception_with_invalid_types()
	{
		$this->beConstructedThrough('factory', [['type' => 'foo']]);
		try { // https://github.com/phpspec/phpspec/issues/610
			$this->getWrappedObject();
			throw new \Exception('Expected exception was not thrown');
		}
		catch(UnknownWorkTypeException $e) {}
	}

	function it_throws_an_exception_with_missing_fields()
	{
		$this->beConstructedThrough('factory', [['type' => 'book']]);
		try { // https://github.com/phpspec/phpspec/issues/610
			$this->getWrappedObject();
			throw new \Exception('Expected exception was not thrown');
		}
		catch(MissingRequiredFieldsException $e) {}
	}

	function it_calls_styles_cite_html_method()
	{
		$this->beConstructedThrough('factory', [['type' => 'book', 'title' => 'foo', 'authors' => ['Ben']]]);

		/** @var Work $work */
		$work = $this;
		$work->citeHtml(new Apa6)->shouldReturn('<i>Foo.</i>   ');
	}
}
