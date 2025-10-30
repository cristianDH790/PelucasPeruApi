<?php
namespace App\Helpers;


class Paginator
{

    private $number = 0;
    private $size = 0;
    private $firstElement = 0;
    private $totalPages = 0;
    private $totalElements = 0;
    private $numberOfElements = 0;
    private $first;
    private $last;

    /**
     * Paginator constructor.
     * @param int $number
     * @param int $size
     * @param int $totalElements
     */
    public function __construct(Int $number, Int $size, Int $totalElements)
    {
        $this->number = $number;
        $this->size = $size;
        $this->totalElements = $totalElements;

        if ( $this->number <= 0 ||  $this->size <= 0 ||  $this->totalElements <= 0) {

            //this.totalElements = 0;
            $this->number = 0;
            $this->size = 0;
            $this->totalPages = 0;
            $this->firstElement = 0;
            $this->numberOfElements = 0;
            $this->first = false;
            $this->last = false;

        }else{
            //$firstElement = 0;
            //$numberOfElements = 0;
            //$totalPages=0;
            // total de pÃ¡ginas
            $this->totalPages = (( $this->totalElements % $size > 0) ? intval(( $this->totalElements / $size) + 1) : intval( $this->totalElements /  $this->size));

            // elemento inicial
            $this->firstElement = ( $this->number *  $this->size -  $this->size);

            // elementos por pagina
            $this->numberOfElements = ( $this->number <  $this->totalPages) ?  $this->firstElement - (( $this->number - 1) *  $this->size -  $this->size)
                : (( $this->totalElements -  $this->firstElement > 0) ?  $this->totalElements -  $this->firstElement : 0);

            // primera pÃ¡gina
            $this->first = ( $this->number == 1) ? true : false;

            // Ãºltima pÃ¡gina
            $this->last = ( $this->number ==  $this->totalPages) ? true : false;
        }


    }

    public function enviar(){

        return [
            "number"=>$this->number,
            "size"=>$this->size,
            "firstElement"=>$this->firstElement,
            "totalPages"=>$this->totalPages,
            "totalElements"=>$this->totalElements,
            "numberOfElements"=>$this->numberOfElements,
            "first"=>$this->first,
            "last"=>$this->last
        ];
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return intval($this->number);
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return intval($this->size);
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getFirstElement()
    {
        return intval($this->firstElement);
    }

    /**
     * @param float|int|Integer|string $firstElement
     */
    public function setFirstElement($firstElement)
    {
        $this->firstElement = $firstElement;
    }

    /**
     * @return float|int|string
     */
    public function getTotalPages()
    {
        return intval($this->totalPages);
    }

    /**
     * @param float|int|string $totalPages
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }

    /**
     * @return int|Integer
     */
    public function getTotalElements()
    {
        return intval($this->totalElements);
    }

    /**
     * @param int|Integer $totalElements
     */
    public function setTotalElements($totalElements)
    {
        $this->totalElements = $totalElements;
    }

    /**
     * @return float|int|Integer|string
     */
    public function getNumberOfElements()
    {
        return intval($this->numberOfElements);
    }

    /**
     * @param float|int|Integer|string $numberOfElements
     */
    public function setNumberOfElements($numberOfElements)
    {
        $this->numberOfElements = $numberOfElements;
    }

    /**
     * @return bool
     */
    public function isFirst()
    {
        return $this->first;
    }

    /**
     * @param bool $first
     */
    public function setFirst($first)
    {
        $this->first = $first;
    }

    /**
     * @return bool
     */
    public function isLast()
    {
        return $this->last;
    }

    /**
     * @param bool $last
     */
    public function setLast($last)
    {
        $this->last = $last;
    }




}
