<?php
/**
 * SQLChunkProcessor.php
 *
 * This file implements the processor for the SQL chunks.
 *
 * PHP version 5
 *
 * LICENSE:
 * Copyright (c) 2010-2014 Justin Swanhart and André Rothe
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author    André Rothe <andre.rothe@phosco.info>
 * @copyright 2010-2014 Justin Swanhart and André Rothe
 * @license   http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @version   SVN: $Id$
 *
 */

namespace PHPSQLParser\processors;

/**
 * This class processes the SQL chunks.
 *
 * @author  André Rothe <andre.rothe@phosco.info>
 * @license http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 *
 */
class SQLChunkProcessor extends AbstractProcessor {

    protected function moveLIKE(&$out) {
        if (!isset($out['TABLE']['like'])) {
            return;
        }
        $out = $this->array_insert_after($out, 'TABLE', array('LIKE' => $out['TABLE']['like']));
        unset($out['TABLE']['like']);
    }



    public function process($out, $blockmode=true) {
        if (!$out) {
            return false;
        }

        if ($blockmode) {
            foreach ($out as $i => &$item) {
                $this->processItems($item);
            }
        } else {
            $this->processItems($out);
        }
        return $out;
    }

    public function processItems(&$out)
    {

        if (!empty($out['BRACKET'])) {
            // TODO: this field should be a global STATEMENT field within the output
            // we could add all other categories as sub_tree, it could also work with multipe UNIONs
            $processor = new BracketProcessor($this->options);
            $processedBracket = $processor->process($out['BRACKET']);
            $remainingExpressions = $processedBracket[0]['remaining_expressions'];

            unset($processedBracket[0]['remaining_expressions']);

            if(!empty($remainingExpressions)) {
                foreach($remainingExpressions as $key=>$expression) {
                    $processedBracket[][$key] = $expression;
                }
            }

            $out['BRACKET'] = $processedBracket;
        }
        if (!empty($out['CREATE'])) {
            $processor = new CreateProcessor($this->options);
            $out['CREATE'] = $processor->process($out['CREATE']);
        }
        if (!empty($out['TABLE'])) {
            $processor = new TableProcessor($this->options);
            $out['TABLE'] = $processor->process($out['TABLE']);
            $this->moveLIKE($out);
        }
        if (!empty($out['INDEX'])) {
            $processor = new IndexProcessor($this->options);
            $out['INDEX'] = $processor->process($out['INDEX']);
        }
        if (!empty($out['EXPLAIN'])) {
            $processor = new ExplainProcessor($this->options);
            $out['EXPLAIN'] = $processor->process($out['EXPLAIN'], array_keys($out));
        }
        if (!empty($out['DESCRIBE'])) {
            $processor = new DescribeProcessor($this->options);
            $out['DESCRIBE'] = $processor->process($out['DESCRIBE'], array_keys($out));
        }
        if (!empty($out['DESC'])) {
            $processor = new DescProcessor($this->options);
            $out['DESC'] = $processor->process($out['DESC'], array_keys($out));
        }
        if (!empty($out['SELECT'])) {
            $processor = new SelectProcessor($this->options);
            $out['SELECT'] = $processor->process($out['SELECT']);
        }
        if (!empty($out['FROM'])) {
            $processor = new FromProcessor($this->options);
            $out['FROM'] = $processor->process($out['FROM']);
        }
        if (!empty($out['USING'])) {
            $processor = new UsingProcessor($this->options);
            $out['USING'] = $processor->process($out['USING']);
        }
        if (!empty($out['UPDATE'])) {
            $processor = new UpdateProcessor($this->options);
            $out['UPDATE'] = $processor->process($out['UPDATE']);
        }
        if (!empty($out['GROUP'])) {
            // set empty array if we have partial SQL statement
            $processor = new GroupByProcessor($this->options);
            $out['GROUP'] = $processor->process($out['GROUP'], isset($out['SELECT']) ? $out['SELECT'] : array());
        }
        if (!empty($out['ORDER'])) {
            // set empty array if we have partial SQL statement
            $processor = new OrderByProcessor($this->options);
            $out['ORDER'] = $processor->process($out['ORDER'], isset($out['SELECT']) ? $out['SELECT'] : array());
        }
        if (!empty($out['LIMIT'])) {
            $processor = new LimitProcessor($this->options);
            $out['LIMIT'] = $processor->process($out['LIMIT']);
        }
        if (!empty($out['WHERE'])) {
            $processor = new WhereProcessor($this->options);
            $out['WHERE'] = $processor->process($out['WHERE']);
        }
        if (!empty($out['HAVING'])) {
            $processor = new HavingProcessor($this->options);
            $out['HAVING'] = $processor->process($out['HAVING'], isset($out['SELECT']) ? $out['SELECT'] : array());
        }
        if (!empty($out['SET'])) {
            $processor = new SetProcessor($this->options);
            $out['SET'] = $processor->process($out['SET'], isset($out['UPDATE']));
        }
        if (!empty($out['DUPLICATE'])) {
            $processor = new DuplicateProcessor($this->options);
            $out['ON DUPLICATE KEY UPDATE'] = $processor->process($out['DUPLICATE']);
            unset($out['DUPLICATE']);
        }
        if (!empty($out['INSERT'])) {
            $processor = new InsertProcessor($this->options);
            $out = $processor->process($out);
        }
        if (!empty($out['REPLACE'])) {
            $processor = new ReplaceProcessor($this->options);
            $out = $processor->process($out);
        }
        if (!empty($out['DELETE'])) {
            $processor = new DeleteProcessor($this->options);
            $out = $processor->process($out);
        }
        if (!empty($out['VALUES'])) {
            $processor = new ValuesProcessor($this->options);
            $out = $processor->process($out);
        }
        if (!empty($out['INTO'])) {
            $processor = new IntoProcessor($this->options);
            $out = $processor->process($out);
        }
        if (!empty($out['DROP'])) {
            $processor = new DropProcessor($this->options);
            $out['DROP'] = $processor->process($out['DROP']);
        }
        if (!empty($out['RENAME'])) {
            $processor = new RenameProcessor($this->options);
            $out['RENAME'] = $processor->process($out['RENAME']);
        }
        if (!empty($out['SHOW'])) {
            $processor = new ShowProcessor($this->options);
            $out['SHOW'] = $processor->process($out['SHOW']);
        }
        if (!empty($out['OPTIONS'])) {
            $processor = new OptionsProcessor($this->options);
            $out['OPTIONS'] = $processor->process($out['OPTIONS']);
        }
        if (!empty($out['WITH'])) {
        	$processor = new WithProcessor($this->options);
        	$out['WITH'] = $processor->process($out['WITH']);
        }
        if(!empty($out['FUNCTION'])){
            $processor = new FunctionProcessor($this->options);
            $out['FUNCTION'] = $processor->process($out['FUNCTION']);
        }
        if(!empty($out['RETURNS'])){
            $processor = new ReturnsProcessor($this->options);
            $out['RETURNS'] = $processor->process($out['RETURNS']);
        }
        if(!empty($out['EXEC'])){
            $processor = new ExecProcessor($this->options);
            $out['EXEC'] = $processor->process($out['EXEC']);
        }
        if(!empty($out['PROCEDURE'])){
            $processor = new ProcedureProcessor($this->options);
            $out['PROCEDURE'] = $processor->process($out['PROCEDURE']);
        }
        if(!empty($out['EXECUTE'])){
            $processor = new ExecuteProcessor($this->options);
            $out['EXECUTE'] = $processor->process($out['EXECUTE']);
        }
        if(!empty($out['DECLARE'])){
            $processor = new DeclareProcessor($this->options);
            $out['DECLARE'] = $processor->process($out['DECLARE']);
        }
        if(!empty($out['BEGIN'])){
            $processor = new BeginProcessor($this->options);
            $out['BEGIN'] = $processor->process($out['BEGIN']);
        }
        if(!empty($out['COMMIT'])){
            $processor = new CommitProcessor($this->options);
            $out['COMMIT'] = $processor->process($out['COMMIT']);
        }
        if(!empty($out['ROLLBACK'])){
            $processor = new RollbackProcessor($this->options);
            $out['ROLLBACK'] = $processor->process($out['ROLLBACK']);
        }
        if(!empty($out['IF'])){
            $processor = new IfProcessor($this->options);
            $out['IF'] = $processor->process($out['IF']);
        }
        if(!empty($out['TRIGGER'])){
            $processor = new TriggerProcessor($this->options);
            $out['TRIGGER'] = $processor->process($out['TRIGGER']);
        }
        if(!empty($out['MERGE'])){
            $processor = new MergeProcessor($this->options);
            $out['MERGE'] = $processor->process($out['MERGE']);
        }
        if(!empty($out['WHEN'])){
            $processor = new WhenProcessor($this->options);
            $out['WHEN'] = $processor->process($out['WHEN']);
        }
        if(!empty($out['THEN'])){
            $processor = new WhenProcessor($this->options);
            $out['THEN'] = $processor->process($out['THEN']);
        }
        if(!empty($out['OPEN'])){
            $processor = new OpenProcessor($this->options);
            $out['OPEN'] = $processor->process($out['OPEN']);
        }
        if(!empty($out['CLOSE'])){
            $processor = new OpenProcessor($this->options);
            $out['CLOSE'] = $processor->process($out['CLOSE']);
        }
        if(!empty($out['DEALLOCATE'])){
            $processor = new OpenProcessor($this->options);
            $out['DEALLOCATE'] = $processor->process($out['DEALLOCATE']);
        }
        if(!empty($out['FETCH'])){
            $processor = new FetchProcessor($this->options);
            $out['FETCH'] = $processor->process($out['FETCH']);
        }
        if(!empty($out['ALTER'])){
            $processor = new AlterProcessor($this->options);
            $out['ALTER'] = $processor->process($out['ALTER']);
        }
        if(!empty($out['AFTER'])){
            $processor = new AfterProcessor($this->options);
            $out['AFTER'] = $processor->process($out['AFTER']);
        }
        if(!empty($out['BEFORE'])){
            $processor = new AfterProcessor($this->options);
            $out['BEFORE'] = $processor->process($out['BEFORE']);
        }
        if(!empty($out['INSTEAD'])){
            $processor = new AfterProcessor($this->options);
            $out['INSTEAD'] = $processor->process($out['INSTEAD']);
        }

        if(!empty($out['FOR'])){
            $processor = new AfterProcessor($this->options);
            $out['FOR'] = $processor->process($out['FOR']);
        }
        if(!empty($out['END'])){
            $processor = new EndProcessor($this->options);
            $out['END'] = $processor->process($out['END']);
        }
        if(!empty($out['UNION'])){
            $processor = new UnionProcessor($this->options);
            $out['UNION'] = $processor->process($out['UNION']);
        }
        if(!empty($out['RETURN'])){
            $processor = new ReturnProcessor($this->options);
            $out['RETURN'] = $processor->process($out['RETURN']);
        }
        if(!empty($out['SYNONYM'])){
            $processor = new SynonymProcessor($this->options);
            $out['SYNONYM'] = $processor->process($out['SYNONYM']);
        }
        if(!empty($out['TYPE'])){
            $processor = new TypeProcessor($this->options);
            $out['TYPE'] = $processor->process($out['TYPE']);
        }
        if(!empty($out['TRUNCATE'])){
            $processor = new TruncateProcessor($this->options);
            $out['TRUNCATE'] = $processor->process($out['TRUNCATE']);
        }
        if(!empty($out['VIEW'])){
            $processor = new ViewProcessor($this->options);
            $out['VIEW'] = $processor->process($out['VIEW']);
        }
        if(!empty($out['RECEIVE'])){
            $processor = new ReceiveProcessor($this->options);
            $out['RECEIVE'] = $processor->process($out['RECEIVE']);
        }
        if(!empty($out['CONVERSATION'])){
            $processor = new ConversationProcessor($this->options);
            $out['CONVERSATION'] = $processor->process($out['CONVERSATION']);
        }
        if(!empty($out['DIALOG'])){
            $processor = new ConversationProcessor($this->options);
            $out['DIALOG'] = $processor->process($out['DIALOG']);
        }
        if(!empty($out['ENABLE'])){
            $processor = new ConversationProcessor($this->options);
            $out['ENABLE'] = $processor->process($out['ENABLE']);
        }
        if(!empty($out['CHECK'])){
            $processor = new SimpleProcessor($this->options);
            $out['CHECK'] = $processor->process($out['CHECK']);
        }
        if(!empty($out['WHILE'])){
            $processor = new ExpressionListProcessor($this->options);
            $out['WHILE'] = $processor->process($out['WHILE']);
        }
        return $out;
    }
}
?>
