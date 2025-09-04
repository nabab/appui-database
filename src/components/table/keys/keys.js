(() => {
  return {
    props: {
      source: {
        type: Object
      }
    },
    data() {
      return {
        root: appui.plugins['appui-database'] + '/',
        hasSelected: false,
      };
    },
    computed: {
      mainMenu(){
        const ret = [];
        ret.push({
          text: bbn._("Create key"),
          icon: 'nf nf-md-key_plus',
          action: this.createColumn
        });
        if (this.hasSelected) {
          const table = this.getRef('table');
          const currentSelected = table?.currentSelected || [];
          const currentSelectedVirtual = bbn.fn.filter(
            currentSelected,
            d => !!bbn.fn.getField(table?.currentData || [], 'data', 'data.name', d)?.is_virtual
          );
          const currentSelectedNoVirtual = bbn.fn.filter(
            currentSelected,
            d => !bbn.fn.getField(table?.currentData || [], 'data', 'data.name', d)?.is_virtual
          );
          ret.push({
            separator: true
          }, {
            icon: 'nf nf-md-trash_can_outline',
            text: bbn._("Drop"),
            action: () => {
              const csNoVirtual = bbn.fn.filter(
                table?.currentSelected || [],
                d => !bbn.fn.getField(table?.currentData || [], 'data', 'data.name', d)?.is_virtual
              );
              this.dropColumn(csNoVirtual)
            }
          }, {
            icon: 'nf nf-md-opera',
            text: bbn._("Options"),
            items: [{
              icon: 'nf nf-md-opera',
              text: currentSelectedVirtual.length ?
                (!currentSelectedNoVirtual.length ? bbn._("Update structure") : bbn._("Store or update structure")) :
                bbn._("Store structure"),
              action: () => {
                const cs = table?.currentSelected || [];
                this.toOption(cs)
              }
            }]
          });
          if (currentSelectedVirtual.length) {
            ret[ret.length - 1].items.push({
              icon: 'nf nf-md-opera',
              text: bbn._("Remove"),
              action: () => {
                const csVirtual = bbn.fn.filter(
                  table?.currentSelected || [],
                  d => !!bbn.fn.getField(table?.currentData || [], 'data', 'data.name', d)?.is_virtual
                );
                this.removeFromOption(csVirtual)
              }
            });
          }
        }

        return ret;
      },
      tableSource() {
        let r = [];
        bbn.fn.iterate(this.source.structure.keys, (a, n) => {
          r.push(bbn.fn.extend({
            name: n
          }, a));
        });
        return r;
      },
      buttons(){
        return [];
      }
    },
    methods: {
      renderColumns(row) {
        return row.columns.join(", ");
      },
      renderConstraint(row) {
        if (row.constraint) {
          return row.ref_table + '.' + row.ref_column
                 + ' &nbsp;&nbsp;&nbsp;<i class="nf nf-fa-info" title="'
                 + bbn._('Database') + ': ' + row.ref_db
                 + '\n' + bbn._('Constraint') + ': ' + row.constraint
                 + '\n' + bbn._('ON UPDATE') + ' ' + row.update
                 + '\n' + bbn._('ON DELETE') + ' ' + row.delete
                 + '"></i>';
        }
        return '-';
      },
      renderRealVirtual(row, col){
        const icon = !!row[col.field] ? 'nf nf-md-check_bold bbn-green' : 'nf nf-md-close_thick bbn-red';
        return '<i class="' + icon + '"></i>';
      },
      onTableToggle(){
        this.hasSelected = !!this.getRef('table')?.currentSelected?.length;
      },
      clearTableSelection(){
        const table = this.getRef('table');
        if (table?.currentSelected?.length) {
          table.currentSelected.splice(0);
        }

        this.hasSelected = false;
      }
    }
  }
})();