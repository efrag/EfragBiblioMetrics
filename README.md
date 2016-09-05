# EfragBiblioMetrics

[![Build Status](https://travis-ci.org/efrag/EfragBiblioMetrics.svg?branch=master)](https://travis-ci.org/efrag/EfragBiblioMetrics)

EfragBiblioMetrics is a library that implements some well known indicators used in Citation Analysis to produce metrics 
to evaluate the scientific impact of a particular scientific publication or author.

## List of Indicators

### Paper Indicators
The paper indicators included in the library are:
* the Number of Citations
* the Contemporary h-Index score for papers
* the PageRank (Base and Normalized)
* the SCEAS1 score for papers
* the SCEAS2 score for papers
* the f-value
* the fp^k-index

### Author Indicators
The author indicators included in the library are:
* the Number of Citations
* the Average Number of Citations
* the h-index
* the g-index
* the Contemporary h-index
* the PageRank (Base and Normalized)
* the SCEAS1 and SCEAS2
* the f-index
* the fa^k-index
* the fas^k-index

## Ranking

For the indicators we are calculating their raw values and their Ordinal ranking.

## Tests

The library has a test suite available that can be executed by running phpunit from the directory of the code. 

There are three test graphs that have been used in the tests. They textual representation of the graphs can be located
under `Tests/Fixtures` and a visual representation of the same graphs can be found under `docs/media`.

