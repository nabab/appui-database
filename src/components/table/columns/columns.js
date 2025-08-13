(() => {
  return {
    props: {
      source: {
        type: Object
      }
    },
    data() {
      return {
        enginesPosition: ['mysql', 'mariadb'],
        hasSelected: false,
        root: appui.plugins['appui-database'] + '/',
      };
    },
    computed: {
      mainMenu(){
        const ret = [];
        ret.push({
          text: bbn._("Create column"),
          icon: 'nf nf-md-table_column_plus_after',
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
      tableSource(){
        return this.source.columns;
      },
      editorOptions() {
        return {
          db: this.source.database,
          host: this.source.id_host,
          engine: this.source.engine,
          table: this.source.name,
          otypes:  this.source.editColumnsData[this.source.engine].types,
          predefined: this.source.editColumnsData[this.source.engine].predefined,
          root: this.source.editColumnsData[this.source.engine].root,
          columns: this.source.columns,
          options: !!this.source.is_virtual
        };
      },
      hasPosition(){
        return this.source.engine && this.enginesPosition.includes(this.source.engine);
      }
    },
    methods: {
      createColumn(){
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
      editColumn(row){
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
      editOption(row) {
        this.getPopup({
          width: '30em',
          height: '15em',
          source: row,
          label: false,
          component: 'appui-database-table-columns-option'
        });
      },
      getButtons(row) {
        const buttons = [];
        if (row.is_real) {
          buttons.push({
            text: bbn._('Edit'),
            action: () => {
              this.editColumn(row);
            },
            icon: 'nf nf-fa-edit'
          });
          if (this.hasPosition) {
            buttons.push({
              text: bbn._('Move Up'),
              action: () => {
                this.moveUp(row);
              },
              icon: 'nf nf-fa-arrow_up',
              disabled: true
            }, {
              text: bbn._('Move Down'),
              action: () => {
                this.moveDown(row);
              },
              icon: 'nf nf-fa-arrow_down',
              disabled: true
            });
          }

          buttons.push({
            text: bbn._('Drop'),
            action: () => {
              this.dropColumn(row);
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
      renderKey(row) {
        return row.key ? `<i class="nf nf-fa-key bbn-m ${row.key === 'PRI' ? 'bbn-primary-text-alt' : 'bbn-tertiary-text-alt'}"></i>` : '';
      },
      renderSigned(row){
        return row.signed ? '' : '<i class="nf nf-fa-check"></i>';
      },
      renderName(row) {
        let cls = [];
        let isDiff = false;
        if (row.is_real && row.is_virtual && row.option) {
          const real = bbn.fn.clone(row);
          const opt = bbn.fn.clone(row.option);
          const realFields = ['name', 'option', 'id_option', 'is_real', 'is_virtual'];
          const optFields = ['id', 'id_alias', 'id_parent', 'code', 'text', 'num', 'num_children'];
          bbn.fn.each(realFields, f => {
            delete real[f];
          });
          bbn.fn.each(optFields, f => {
            delete opt[f];
          });
          isDiff = !bbn.fn.isSame(real, opt);
        }

        if (isDiff) {
          cls.push('bbn-error-text');
        }

        return `<span class="${cls.join(' ')}">${row.name}</span>`;
      },
      renderType(row){
        return row.type + (row.maxlength ? ' (' + row.maxlength + ')' : '');
      },
      renderNull(row) {
        return row.null ? '<i class="nf nf-fa-check"></i>' : '';
      },
      renderRealVirtual(row, col){
        const icon = !!row[col.field] ? 'nf nf-fa-check bbn-green' : 'nf nf-fa-times bbn-red';
        return '<i class="' + icon + '"></i>';
      },
      toOption(row) {
        let mess;
        let column;
        if (bbn.fn.isArray(row) && (row.length === 1)) {
          row = row[0].name || row[0];
        }

        if (bbn.fn.isArray(row)) {
          column = bbn.fn.map(row, d => d.name || d);
          mess = bbn._(
            "Are you sure you want to store the structure of the columns %s as options?",
            bbn.fn.map(row, d => '"' + d + '"').join(", ")
          );
        }
        else {
          column = row.name || row;
          mess = bbn._("Are you sure you want to store the structure of the column \"%s\" as options?", column);
        }

        this.confirm(mess, () => {
          this.post(this.root + 'actions/column/options', {
            host_id: this.source.id_host,
            db: this.source.database,
            table: this.source.name,
            column
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
        let mess;
        let column;
        if (bbn.fn.isArray(row) && (row.length === 1)) {
          row = row[0].name || row[0];
        }

        if (bbn.fn.isArray(row)) {
          column = bbn.fn.map(row, d => d.name || d);
          mess = bbn._(
            "Are you sure you want to remove the columns %s from options?",
            bbn.fn.map(row, d => '"' + d + '"').join(", ")
          );
        }
        else {
          column = row.name || row;
          mess = bbn._("Are you sure you want to remove the column \"%s\" from options?", column);
        }

        this.confirm(mess, () => {
          this.post(this.root + 'actions/column/options', {
            host_id: this.source.id_host,
            db: this.source.database,
            table: this.source.name,
            column,
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
      dropColumn(row) {
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
      insertColumn(data) {
        this.$set(this.source.structure.fields, data.name);
        this.$forceUpdate();
        this.$nextTick(() => {
          this.getRef('table').updateData();
        });
      },
      update(data, col, idx) {
        let cp = this.closest('bbn-container');
        data.olddecimal = data.decimal;
        data.olddefault = data.default;
        data.olddefaultExpression = data.defaultExpression;
        data.oldname = data.name;
        data.oldtype = data.type;
        data.oldsigned = data.signed;
        data.oldnull = data.null;
        data.oldkey = data.key;
        data.oldmaxlength = data.maxlength;
        data.oldindex = data.index;
        let editColumnsData = this.source.editColumnsData;
        this.getPopup({
          label: 'Edit a column',
          component: 'appui-database-column-editor',
          source : {
            db: cp.source.db,
            host: cp.source.host,
            engine: cp.source.engine,
            table: cp.source.table,
            otypes:  editColumnsData.mysql.types,
            predefined: editColumnsData.mysql.predefined,
            source: data,
            root: editColumnsData.mysql.root,
          },
        });
        return;
      },
      moveUp(idx) {
        if (idx.position > 1) {
          let tmp = idx.position - 1;
          bbn.fn.move(this.tableSource, tmp, tmp - 1);
          bbn.fn.log('idx + tableSource', idx, this.tableSource);
        }
        return;
      },
      moveDown (idx) {
        if (idx.position < this.tableSource.length) {
          let tmp = idx.position - 1;
          bbn.fn.move(this.tableSource, tmp, tmp + 1);
          //bbn.fn.moveColumn(this.tableSource, tmp, tmp + 1);
        }
        return;
      },
      changeColPosition() {
        return;
      },
      makePredefined() {
        return;
      },
      addKey() {
        return;
      },
    }
  };
})();
