<?php

namespace Sabre\CalDAV\Xml\Request;

use Sabre\Xml\Reader;
use Sabre\Xml\XmlDeserializable;

/**
 * MKCALENDAR parser.
 *
 * This class parses the MKCALENDAR request, as defined in:
 *
 * https://agents.ietf.org/html/rfc4791#section-5.3.1
 *
 * @copyright Copyright (C) fruux GmbH (https://fruux.com/)
 * @author Evert Pot (http://evertpot.com/)
 * @license http://sabre.io/license/ Modified BSD License
 */
class MkCalendar implements XmlDeserializable {

    /**
     * The list of properties that will be set.
     *
     * @var array
     */
    public $properties = [];

    /**
     * Returns the list of properties the calendar will be initialized with.
     *
     * @return array
     */
    function getProperties() {

        return $this->properties;

    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * This method is called statically, this is because in theory this method
     * may be used as a type of constructor, or factory method.
     *
     * Often you want to return an instance of the current class, but you are
     * free to return other data as well.
     *
     * You are responsible for advancing the reader to the next element. Not
     * doing anything will result in a never-ending loop.
     *
     * If you just want to skip parsing for this element altogether, you can
     * just call $reader->next();
     *
     * $reader->parseInnerTree() will parse the entire sub-tree, and advance to
     * the next element.
     *
     * @param Reader $reader
     * @return mixed
     */
    static function xmlDeserialize(Reader $reader) {

        $self = new self();

        $elementMap = $reader->elementMap;
        $elementMap['{DAV:}prop'] = 'Sabre\DAV\Xml\Element\Prop';
        $elementMap['{DAV:}set'] = 'Sabre\Xml\Element\KeyValue';
        $elems = $reader->parseInnerTree($elementMap);

        foreach ($elems as $elem) {
            if ($elem['name'] === '{DAV:}set') {
                $self->properties = array_merge($self->properties, $elem['value']['{DAV:}prop']);
            }
        }

        return $self;

    }

}
