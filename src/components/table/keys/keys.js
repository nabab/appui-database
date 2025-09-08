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
              this.dropKey(csNoVirtual)
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
      getButtons(row) {
        const buttons = [];
        if (row.is_real) {
          buttons.push({
            text: bbn._('Edit'),
            action: () => {
              this.editKey(row);
            },
            icon: 'nf nf-fa-edit'
          }, {
            text: bbn._('Drop'),
            action: () => {
              this.dropKey(row);
            },
            icon: 'nf nf-md-trash_can_outline'
          });
        }

        const optBts = [];
        if (row.is_virtual) {
          optBts.push({
            text: bbn._('Edit'),
            action: () => this.editOption(row),
            icon: 'nf nf-fa-edit',
            disabled: true
          });
          if (row.is_real) {
              optBts.push({
              text: bbn._("Update structure"),
              action: () => this.toOption(row),
              icon: 'nf nf-md-update',
            });
          }

          optBts.push({
            text: bbn._("Remove"),
            action: () => this.removeFromOption(row),
            icon: 'nf nf-md-trash_can_outline',
          });
        }
        else if (row.is_real) {
          optBts.push({
            text: bbn._("Store structure"),
            action: () => this.toOption(row),
            icon: 'nf nf-md-content_save_cog_outline',
          });
        }

        if (optBts.length) {
          buttons.push({
            text: bbn._("Options"),
            icon: 'nf nf-md-opera',
            items: optBts
          });
        }

        return buttons;
      },
      trClass(row){
        if (row?.is_virtual && !row?.is_real) {
           return 'bbn-i';
        }
      },
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
      },
      createKey(){
        return;
        (this.main || this).getPopup({
          label: bbn._("Add a column"),
          component: 'appui-database-column-editor',
          componentOptions: bbn.fn.extend({
            source: {
              name: "",
              maxlength: null,
              decimals: null,
              type: '',
              defaultExpression: 0,
              default: '',
              extra: '',
              signed: 1,
              "null": 0,
              ref_table: '',
              ref_column: '',
              index: '',
              delete:'CASCADE',
              update:'CASCADE',
              charset: '',
              collation: ''
            }
          }, this.editorOptions),
          componentEvents: {
            success: d => {
              this.main.reload();
            }
          }
        });
      },
      editKey(row){
        return;
        (this.main || this).getPopup({
          label: bbn._("Edit a column"),
          component: 'appui-database-column-editor',
          componentOptions: bbn.fn.extend(
            {
              source: row
            },
            this.editorOptions,
            {
              predefined: []
            }
          ),
          componentEvents: {
            success: d => {
              this.main.reload();
            }
          }
        });
      },
      dropKey(row){
        return;
        let options = false;
        let column = '';
        if (bbn.fn.isArray(row) && (row.length === 1)) {
          row = row[0].name || row[0];
        }

        if (bbn.fn.isArray(row)) {
          column = bbn.fn.map(row, d => d.name || d);
          options = !!bbn.fn.filter(row, d => {
            return !!bbn.fn.getField(
              this.getRef('table').currentData,
              'data',
              'data.name',
              d.name || d
            ).is_virtual;
          }).length;
        }
        else {
          column = row.name || row;
          options = !!row.is_virtual;
        }

        if (this.source.id_host
          && this.source.database
          && this.source.name
          && column.length
        ) {
          this.getPopup({
            label: false,
            component: 'appui-database-table-columns-drop',
            componentOptions: {
              host: this.source.id_host,
              database: this.source.database,
              table: this.source.name,
              column,
              options
            },
            componentEvents: {
                success: () => {
                  this.main.reload();
                }
              }
          });
        }
      },
      toOption(row) {
        return;
        let mess;
        let key;
        if (bbn.fn.isArray(row) && (row.length === 1)) {
          row = row[0].name || row[0];
        }

        if (bbn.fn.isArray(row)) {
          key = bbn.fn.map(row, d => d.name || d);
          mess = bbn._(
            "Are you sure you want to store the structure of the key %s as options?",
            bbn.fn.map(row, d => '"' + d + '"').join(", ")
          );
        }
        else {
          key = row.name || row;
          mess = bbn._("Are you sure you want to store the structure of the key \"%s\" as options?", key);
        }

        this.confirm(mess, () => {
          this.post(this.root + 'actions/key/options', {
            host_id: this.source.id_host,
            db: this.source.database,
            table: this.source.name,
            key
          }, d => {
            if (d.success) {
              appui.success();
              this.main.reload();
            }
            else {
              appui.error();
            }
          }, () => {
            appui.error(bbn._('An error occurred'));
          });
        });
      },
      removeFromOption(row) {
        return;
        let mess;
        let key;
        if (bbn.fn.isArray(row) && (row.length === 1)) {
          row = row[0].name || row[0];
        }

        if (bbn.fn.isArray(row)) {
          key = bbn.fn.map(row, d => d.name || d);
          mess = bbn._(
            "Are you sure you want to remove the keys %s from options?",
            bbn.fn.map(row, d => '"' + d + '"').join(", ")
          );
        }
        else {
          key = row.name || row;
          mess = bbn._("Are you sure you want to remove the key \"%s\" from options?", key);
        }

        this.confirm(mess, () => {
          this.post(this.root + 'actions/key/options', {
            host_id: this.source.id_host,
            db: this.source.database,
            table: this.source.name,
            key,
            remove: true
          }, d => {
            if (d.success) {
              appui.success();
              this.main.reload();
            }
            else {
              appui.error();
            }
          }, () => {
            appui.error(bbn._('An error occurred'));
          });
        });
      },
    }
  }
})();