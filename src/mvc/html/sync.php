<bbn-router :autoload="false"
            mode="tabs">
  <bbns-container label="<?= _('Errors') ?>"
                  url="errors"
                  :fixed="true"
                  icon="nf nf-pom-internal_interruption"
                  :source="source"
                  component="appui-database-sync-errors">
  </bbns-container>
  <bbns-container label="<?= _('Conflicts') ?>"
                  url="conflicts"
                  :fixed="true"
                  icon="nf nf-md-vector_difference"
                  :source="source"
                  component="appui-database-sync-conflicts">
  </bbns-container>
  <bbns-container label="<?= _('Structures') ?>"
                  url="structures"
                  :fixed="true"
                  icon="nf nf-md-table_settings"
                  :source="source"
                  component="appui-database-sync-structures">
  </bbns-container>
</bbn-router>