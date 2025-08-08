(() => {
  return {
    props: {
      source: {
        type: Object
      }
    },
    data() {
      return {
        cp: null,
      };
    },
    computed: {
      mainMenu(){
        return [{
          text: bbn._("Add column"),
          icon: 'nf nf-md-table_column_plus_after',
          action: this.addColumn
        }];
      },
      tableSource(){
        let r = [];
        bbn.fn.iterate(this.source.structure.fields, (a, n) => {
          r.push(bbn.fn.extend({name: n}, a));
        });
        return r;
      },
      editorOptions() {
        let editColumnsData = this.source.editColumnsData;
        return this.cp ? {
          db: this.cp.source.db,
          host: this.cp.source.host,
          engine: this.cp.source.engine,
          table: this.cp.source.table,
          otypes:  editColumnsData[this.cp.source.engine].types,
          predefined: editColumnsData[this.cp.source.engine].predefined,
          root: editColumnsData[this.cp.source.engine].root,
          columns: this.tableSource
        } : {};
      }
    },
    methods: {
      addColumn(){
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
          }, this.editorOptions)
        });
      },
      editOption(row) {
        bbn.fn.log("Hello", arguments);
        this.getPopup({
          width: '30em',
          height: '15em',
          source: row,
          label: false,
          component: 'appui-database-table-columns-option'
        });
      },
      getButtons(row) {
        let button = [{
          text: bbn._('Edit column'),
          action: 'edit',
          icon: 'nf nf-fa-edit'
        }, {
          text: bbn._('Remove'),
          action: () => {
            this.removeItem(row);
          },
          icon: 'nf nf-fa-times'
        }, {
          text: bbn._('Move Up'),
          action: () => {
            this.moveUp(row);
          },
          icon: 'nf nf-fa-arrow_up'
        }, {
          text: bbn._('Move Down'),
          action: () => {
            this.moveDown(row);
          },
          icon: 'nf nf-fa-arrow_down'
        }];
        if (row.option) {
          button.push({
            text: bbn._('Edit Option'),
            action: () => {
              this.editOption(row);
            },
            icon: 'nf nf-fa-edit'
          });
        }

        return button;
      },
      getStateColor(row) {
        let col = false;
        if (!row.option) {
          col = 'red';
        }
        return col;
      },
      renderKey(row) {
        return row.key ? `<i class="nf nf-fa-key bbn-m ${row.key === 'PRI' ? 'bbn-primary-text-alt' : 'bbn-tertiary-text-alt'}"></i>` : '';
      },
      renderSigned(row){
        return row.signed ? '' : '<i class="nf nf-fa-check"></i>';
      },
      renderName(row) {
        let col = this.getStateColor(row);
        let st = '';
        if (row.option && (row.option.text != row.name)) {
          st += row.option.text + " (" + row.name + ")";
        }
        else {
          st += '<span' + (col ? ' class="bbn-' + col + '"' : '') + '>' + row.name + '</span>';
        }
        if (this.source.constraints[row.name]) {
          st += ' (' + bbn._('refers to') + ' ' + this.source.constraints[row.name].column + ' ' + bbn._('in') + ' ' + this.source.constraints[row.name].table + ')';
        }
        return st;
      },
      renderType(row){
        return row.type + (row.maxlength ? ' (' + row.maxlength + ')' : '');
      },
      renderNull(row) {
        return row.null ? '<i class="nf nf-fa-check"></i>' : '';
      },
      removeItem(data) {
        this.confirm(bbn._("Are you sure you want to delete this column?"), () => {
          const requestData = {
            engine: this.cp.source.engine,
            host: this.cp.source.host,
            table: this.cp.source.table,
            db: this.cp.source.db,
            column: data,
          };
          bbn.fn.post(appui.plugins['appui-database'] + '/actions/column/remove', requestData, d => {
            if (d.success) {
              delete this.source.structure.fields[data.name];
              this.$forceUpdate();
              this.$nextTick( () => {
                this.getRef('table').updateData();
              });
            }
          });
        });
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
    },
    mounted() {
      this.cp = this.closest('bbn-container');
    }
  };
})();
