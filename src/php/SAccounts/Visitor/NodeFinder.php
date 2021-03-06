<?php
/**
 * Simple Double Entry Accounting V3
 
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2018, UK
 * @license BSD-3-Clause See LICENSE.md
 */
namespace SAccounts\Visitor;

use Tree\Visitor\Visitor;
use Tree\Node\NodeInterface;
use Tree\Node\Node;
use SAccounts\Nominal;
use SAccounts\Account;

/**
 * Find an account node in the chart tree
 */
class NodeFinder implements Visitor
{
    /**
     * @var Nominal
     */
    protected $valueToFind;

    /**
     * @param Nominal $valueToFind Node value to find
     */
    public function __construct(Nominal $valueToFind)
    {
        $this->valueToFind = $valueToFind->get();
    }

    /**
     * @param NodeInterface $node
     *
     * @return NodeInterface|null
     */
    public function visit(NodeInterface $node)
    {
        $currAc = $node->getValue();

        if ($currAc instanceof Account && $currAc->getNominal()->get() == $this->valueToFind) {
            return $node;
        }

        foreach ($node->getChildren() as $child) {
            /** @noinspection PhpVoidFunctionResultUsedInspection */
            $found = $child->accept($this);
            if (!is_null($found)) {
                return $found;
            }
        }

        return null;
    }
}