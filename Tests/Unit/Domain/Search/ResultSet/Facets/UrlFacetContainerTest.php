<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace ApacheSolrForTypo3\Solr\Tests\Unit\Domain\Search\ResultSet\Facets;

use ApacheSolrForTypo3\Solr\Domain\Search\ResultSet\Facets\UrlFacetContainer;
use ApacheSolrForTypo3\Solr\System\Util\ArrayAccessor;
use ApacheSolrForTypo3\Solr\Tests\Unit\SetUpUnitTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Testcases for the url data bag
 *
 * @author Lars Tode <lars.tode@dkd.de>
 */
class UrlFacetContainerTest extends SetUpUnitTestCase
{
    /**
     * Test data for index based url parameters
     */
    protected array $indexParameters = [
        'tx_solr' => [
            'filter' => [
                'type:pages',
                'type:example',
                'type:news',
                'created:12345',
            ],
        ],
    ];
    /**
     * Test data for assoc based url parameters
     */
    protected array $assocParameters = [
        'tx_solr' => [
            'filter' => [
                'type:pages' => 1,
                'type:example' => 1,
                'type:news' => 1,
                'created:12345' => 1,
            ],
        ],
    ];

    #[Test]
    public function canFilterIndexFacetsParameterByName()
    {
        $urlFacetBack = new UrlFacetContainer(new ArrayAccessor($this->indexParameters));
        $urlFacetBack->enableSort();
        self::assertEquals(['example', 'news', 'pages'], $urlFacetBack->getActiveFacetValuesByName('type'));
    }

    #[Test]
    public function canFilterAssocFacetsParameterByName()
    {
        $urlFacetBack = new UrlFacetContainer(
            new ArrayAccessor($this->assocParameters),
            'tx_solr',
            'assoc'
        );
        self::assertEquals(['example', 'news', 'pages'], $urlFacetBack->getActiveFacetValuesByName('type'));
    }

    #[Test]
    public function canRemoveAllIndexFacetsParameter()
    {
        $urlFacetBack = new UrlFacetContainer(new ArrayAccessor($this->indexParameters));
        self::assertEquals(4, $urlFacetBack->count());
        $urlFacetBack->removeAllFacets();
        self::assertEquals(0, $urlFacetBack->count());
    }

    #[Test]
    public function canRemoveAllAssocFacetsParameter()
    {
        $urlFacetBack = new UrlFacetContainer(
            new ArrayAccessor($this->assocParameters),
            'tx_solr',
            'assoc'
        );
        self::assertEquals(4, $urlFacetBack->count());
        $urlFacetBack->removeAllFacets();
        self::assertEquals(0, $urlFacetBack->count());
    }

    #[Test]
    public function canRemoveAllFacetsParameterByName()
    {
        $urlFacetBack = new UrlFacetContainer(new ArrayAccessor($this->indexParameters));
        self::assertEquals(4, $urlFacetBack->count());
        $urlFacetBack->removeAllFacetValuesByName('type');
        self::assertEquals(1, $urlFacetBack->count());
    }

    #[Test]
    public function canRemoveAllAssocFacetsParameterByName()
    {
        $urlFacetBack = new UrlFacetContainer(
            new ArrayAccessor($this->assocParameters),
            'tx_solr',
            'assoc'
        );
        self::assertEquals(4, $urlFacetBack->count());
        $urlFacetBack->removeAllFacetValuesByName('type');
        self::assertEquals(1, $urlFacetBack->count());
    }

    #[Test]
    public function canRemoveASingleFacetParameterByName()
    {
        $urlFacetBack = new UrlFacetContainer(new ArrayAccessor($this->indexParameters));
        self::assertEquals(4, $urlFacetBack->count());
        $urlFacetBack->removeFacetValue('type', 'example');
        self::assertEquals(3, $urlFacetBack->count());
    }

    #[Test]
    public function canRemoveASingleAssocFacetParameterByName()
    {
        $urlFacetBack = new UrlFacetContainer(
            new ArrayAccessor($this->assocParameters),
            'tx_solr',
            'assoc'
        );
        $urlFacetBack->removeFacetValue('type', 'example');
        self::assertEquals(3, $urlFacetBack->count());
    }

    #[Test]
    public function keepOrderingOfIndexParameters()
    {
        $urlFacetBack = new UrlFacetContainer(new ArrayAccessor($this->indexParameters));
        self::assertEquals(['pages', 'example', 'news'], $urlFacetBack->getActiveFacetValuesByName('type'));
    }

    #[Test]
    public function canSortIndexParameters()
    {
        $urlFacetBack = new UrlFacetContainer(new ArrayAccessor($this->indexParameters));
        $urlFacetBack->enableSort();
        self::assertEquals(['example', 'news', 'pages'], $urlFacetBack->getActiveFacetValuesByName('type'));
    }

    #[Test]
    public function didNotKeepOrderingOfAssocParameters()
    {
        $urlFacetBack = new UrlFacetContainer(
            new ArrayAccessor($this->assocParameters),
            'tx_solr',
            'assoc'
        );
        self::assertNotEquals(
            ['pages', 'example', 'news'],
            $urlFacetBack->getActiveFacetValuesByName('type')
        );
    }

    #[Test]
    public function assocParametersSortedByDefault()
    {
        $urlFacetBack = new UrlFacetContainer(
            new ArrayAccessor($this->assocParameters),
            'tx_solr',
            'assoc'
        );
        self::assertEquals(['example', 'news', 'pages'], $urlFacetBack->getActiveFacetValuesByName('type'));
    }
}
