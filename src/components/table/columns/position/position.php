<bbn-form class="appui-database-table-columns-position bbn-lpadding bbn-c bbn-lg"
          :action="action"
          :source="formSource"
          submit-text="<?=_('Yes')?>"
          cancel-text="<?=_('No')?>"
          :prefilled="true"
          @success="onSuccess"
          @error="onError">
  <div bbn-text="message"/>
  <div bbn-if="options"
       class="bbn-middle bbn-top-space">
    <bbn-switch bbn-model="formSource.options"
                class="bbn-right-sspace"
                :value="true"
                :novalue="false"/>
    <span><?=_('Change position on options')?></span>
  </div>
</bbn-form>
