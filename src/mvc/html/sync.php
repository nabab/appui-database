<bbn-router :autoload="false"
            :nav="true">
  <bbns-container title="<?=_('Errors')?>"
                  url="errors"
                  :static="true"
                  icon="nf nf-pom-internal_interruption"
                  :source="source"
                  component="appui-database-sync-errors">
  </bbns-container>
  <bbns-container title="<?=_('Conflicts')?>"
                  url="conflicts"
                  :static="true"
                  icon="nf nf-mdi-vector_difference"
                  :source="source"
                  component="appui-database-sync-conflicts">
  </bbns-container>
</bbn-router>