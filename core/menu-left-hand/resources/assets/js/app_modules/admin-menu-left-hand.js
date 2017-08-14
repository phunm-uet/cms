(function () {
    'use strict';
    var $, Item, cleanItemForOutput, createItemForModal,
        dataBound, dataChange, deleteItem, getIconFromModal, getNameFromModal,
        itemToJSON, modalNeedsName, modalNeedsNameNoMore,
        refreshNodeWithItem, saveChanges, showDeleteModalForItem,
        showEditModalForItem, updateItemForModal, validateDrag, isDirty,
        scrollTimeout, scrollIntensity, DRAG_SCROLL_PERCENT, DRAG_SCROLL_DELAY,
        doScroll, isSaving;

    $ = jQuery;

    isDirty = false;
    isSaving = false;

    scrollTimeout = null;
    scrollIntensity = 0;
    DRAG_SCROLL_PERCENT = 10;
    DRAG_SCROLL_DELAY = 100;

    if (!String.prototype.includes) {
        String.prototype.includes = function () {
            return String.prototype.indexOf.apply(this, arguments) !== -1;
        };
    }

    if (Modernizr._version[0] === '2') {
        delete Modernizr.flexbox;
        Modernizr.addTest('flexbox', Modernizr.testAllProps('flexBasis', '1px', true));
    }

    validateDrag = function (event) {
        var dropTargetItem, parent, sourceItem, $window, dragY, windowHeight,
            dragScrollUpBoundry, dragScrollDownBoundry;

        dropTargetItem = this.dataItem(event.dropTarget);
        if (!((dropTargetItem != null) && (dropTargetItem.kind != null))) {
            return;
        }
        sourceItem = this.dataItem(event.sourceNode);
        if (event.statusClass === 'k-add' || event.statusClass === 'add') {
            if ((dropTargetItem.kind === 'page') || (sourceItem.kind === 'category' && dropTargetItem.kind === 'category')) {
                event.setStatusClass('k-denied');
            }
        }
        if (sourceItem.kind === 'category' && event.statusClass.includes('insert')) {
            parent = this.parent(event.dropTarget);
            if (parent.length !== 0) {
                event.setStatusClass('k-denied');
            }
        }

        // Scroll window when near top or bottom of the page
        $window = $(window);
        dragY = event.pageY - $window.scrollTop();
        windowHeight = $window.height();
        dragScrollUpBoundry = windowHeight * DRAG_SCROLL_PERCENT / 100.0;
        dragScrollDownBoundry = windowHeight - dragScrollUpBoundry;
        if (dragY < dragScrollUpBoundry) {
            scrollIntensity = dragY - dragScrollUpBoundry;
        } else if (dragY > dragScrollDownBoundry) {
            scrollIntensity = dragY - dragScrollDownBoundry;
        } else {
            scrollIntensity = 0;
        }

        if (scrollIntensity !== 0 && scrollTimeout == null) {
            doScroll();
        }
    };

    doScroll = function () {
        if ($('.k-drag-clue').length === 0) {
            scrollIntensity = 0;
        }

        if (scrollIntensity === 0) {
            scrollTimeout = null;
        } else {
            window.scrollBy(0, scrollIntensity);
            scrollTimeout = setTimeout(doScroll, DRAG_SCROLL_DELAY);
        }
    };

    dataBound = function (event) {
        var i, item, items, len, node;
        item = this.dataItem(event.node);
        if (item != null) {
            refreshNodeWithItem(event.node, item);
        } else {
            items = this.dataSource.data();
            for (i = 0, len = items.length; i < len; i++) {
                item = items[i];
                node = this.findByUid(item.uid);
                refreshNodeWithItem(node, item);
            }
        }
    };

    refreshNodeWithItem = function (node, item) {
        if (item.kind === 'category' && (item.children != null)) {
            $(node).find('>div .item-count').text(item.items.length);
        }
    };

    dataChange = function (event) {
        var allFields, i, icon, iconClass, item, len, ref;
        allFields = event.field == null;
        ref = event.items;
        for (i = 0, len = ref.length; i < len; i++) {
            item = ref[i];
            if (allFields || event.field === 'icon') {
                if (item.icon === item.defaultIcon) {
                    item.icon = null;
                }
                icon = item.icon || item.defaultIcon;
                iconClass = icon;
                item.set('iconClass', iconClass);
            }
            if ((allFields || event.field === 'name') && item.name === '') {
                item.name = null;
            }
            if (allFields || event.field === 'name' || event.field === 'defaultName') {
                if (item.name === item.defaultName) {
                    item.name = null;
                }
                item.displayName = item.name || item.defaultName || '';
            }
            if (item.kind === 'category' && (item.items == null)) {
                item.items = [];
            }
        }
    };

    saveChanges = function (treeView) {
        var i, item, items, len, ref;
        items = [];
        ref = treeView.dataSource.data();
        for (i = 0, len = ref.length; i < len; i++) {
            item = ref[i];
            item = item.toJSON();
            items.push(item);
        }
        $('#items').val(JSON.stringify(items));
        $('#menuLeftHandForm').submit();
    };

    cleanItemForOutput = function (item) {
        var child, i, len, ref;
        delete item.iconClass;
        delete item.displayName;
        if (item.items != null) {
            ref = item.items;
            for (i = 0, len = ref.length; i < len; i++) {
                child = ref[i];
                cleanItemForOutput(child);
            }
        }
        return item;
    };

    updateItemForModal = function ($editModal, item) {
        var name;
        name = getNameFromModal($editModal);
        if ((name != null) || (item.defaultName != null)) {
            item.set('name', name);
            item.set('icon', getIconFromModal($editModal));
            return true;
        } else {
            modalNeedsName($editModal);
            return false;
        }
    };

    createItemForModal = function ($editModal, treeView) {
        var item, name;
        name = getNameFromModal($editModal);
        if (name != null) {
            item = new Item({
                kind: 'category',
                userItem: true,
                name: name,
                icon: getIconFromModal($editModal)
            });
            return treeView.dataSource.add(item);
        } else {
            modalNeedsName($editModal);
            return false;
        }
    };

    modalNeedsName = function ($editModal) {
        var $name;
        $name = $editModal.find('.item-name');
        $name.on('change keyup', modalNeedsNameNoMore);
        $name.tooltip({
            title: 'You need to provide a name',
            placement: 'bottom',
            trigger: 'hover focus'
        }).focus();
    };

    modalNeedsNameNoMore = function () {
        var $this;
        $this = $(this);
        if ($this.val() !== '') {
            $this.tooltip('destroy');
            $this.off('change keyup', modalNeedsNameNoMore);
        }
    };

    getNameFromModal = function ($editModal) {
        var $name, name;
        $name = $editModal.find('.item-name');
        if ($name == null) {
            return null;
        }
        name = $name.val();
        if (name !== '') {
            return name;
        } else {
            return null;
        }
    };

    getIconFromModal = function ($editModal) {
        return $editModal.find('.item-icon').val() || null;
    };

    deleteItem = function (treeView, item) {
        var child, childNode, node;
        node = treeView.findByUid(item.uid);
        if (typeof(item.items) != 'undefined') {
            while (item.items.length > 0) {
                child = item.items[0];
                childNode = treeView.findByUid(child.uid);
                treeView.insertBefore(childNode, node);
            }
        }
        treeView.dataSource.remove(item);
    };

    showEditModalForItem = function ($editModal, item) {
        var $defaultIcon, $defaultName, $name, icon, iconClass;
        $defaultName = $editModal.find('.item-default-name');
        $defaultIcon = $editModal.find('.item-default-icon');
        $name = $editModal.find('.item-name');
        $name.tooltip('destroy');
        icon = $editModal.find('.item-icon');
        if (item.uid != null) {
            $editModal.removeClass('modal-for-new');
            $editModal.data('uid', item.uid);
            if (item.defaultName != null) {
                $defaultName.text(item.defaultName);
                $name.attr('placeholder', item.defaultName);
            } else {
                $defaultName.html('<i>(No default name)</i>');
                $name.removeAttr('placeholder');
            }
            if (item.icon != null) {
                icon.val(item.icon);
            } else {
                iconClass = item.defaultIcon;
                iconClass = "item-default-icon " + iconClass;
                icon.attr('placeholder', item.defaultIcon);
            }
            $defaultIcon.attr('class', iconClass);
            $name.val(item.name || item.defaultName || '');
            icon = item.icon || item.defaultIcon;
        } else {
            $editModal.addClass('modal-for-new');
            $editModal.removeData('uid');
            $name.val('');
            $name.removeAttr('placeholder');
        }
        $editModal.find('.item-defaults-group').toggleClass('hidden', item.userItem === true);
        $editModal.modal('show');
    };

    showDeleteModalForItem = function ($deleteModal, item) {
        var $icon, $itemPreview, icon, iconClass, name;
        $deleteModal.data('uid', item.uid);
        $itemPreview = $deleteModal.find('.item-preview');
        $icon = $itemPreview.find('.fi');
        icon = item.icon || item.defaultIcon;
        iconClass = icon;
        $icon.attr('class', iconClass);
        name = item.name || item.defaultName || ("(Unnamed " + item.kind + ")");
        $itemPreview.find('.name').text(name);
        $deleteModal.modal('show');
    };

    itemToJSON = function (item) {
        var child, i, json, len, ref;
        json = {
            kind: item.kind
        };
        if (item.id != null) {
            json.id = item.id;
        }
        if (item.name != null) {
            json.name = item.name;
        }
        if (item.route != null) {
            json.route = item.route;
        }
        if (item.linked_routes != null) {
            json.linked_routes = item.linked_routes;
        }
        if (item.feature_id != null) {
            json.feature_id = item.feature_id;
        }
        if (item.icon != null) {
            json.icon = item.icon;
        }
        if (item.userItem) {
            json.userItem = true;
        } else {
            if (item.defaultName != null) {
                json.defaultName = item.defaultName;
            }
            if (item.defaultIcon != null) {
                json.defaultIcon = item.defaultIcon;
            }
        }
        if (item.items != null) {
            json.items = [];
            ref = item.items;
            for (i = 0, len = ref.length; i < len; i++) {
                child = ref[i];
                json.items.push(itemToJSON(child));
            }
        }
        return json;
    };

    Item = kendo.data.Node.define({
        id: 'id',
        children: 'items',
        fields: {
            id: {
                type: 'number',
                editable: false,
                nullable: true
            },
            kind: {
                type: 'string',
                editable: false
            },
            name: {
                type: 'string',
                nullable: true
            },
            defaultName: {
                type: 'string',
                editable: false
            },
            feature_id: {
                type: 'number',
                editable: false,
                nullable: true
            },
            route: {
                type: 'string',
                editable: false,
                nullable: true
            },
            linked_routes: {
                type: 'string',
                editable: false,
                nullable: true
            },
            icon: {
                type: 'string',
                nullable: true
            },
            defaultIcon: {
                type: 'string',
                editable: false,
                nullable: true
            },
            userItem: {
                type: 'boolean',
                editable: false,
                defaultValue: false
            },
            displayName: {
                type: 'string'
            },
            iconClass: {
                type: 'string'
            }
        },
        toJSON: function () {
            return itemToJSON(this);
        }
    });

    $(function () {
        var $resetDefaultsModal, $resetSavedModal, $deleteModal, $editModal, $menu, treeView, dirty;

        $menu = $('#menu-left-hand-administration');
        $editModal = $menu.find('.modal-for-edit');
        $deleteModal = $menu.find('.modal-for-delete');
        $resetDefaultsModal = $menu.find('.modal-for-reset-defaults');
        $resetSavedModal = $menu.find('.modal-for-reset-saved');
        $menu.data('current-json', $menu.data('json'));

        dirty = function (value) {
            isDirty = !!value;

            if (isDirty) {
                // Unsaved changes
                $menu.find('.action-save-tree').prop('disabled', false);
            } else {
                // Changes are saved
                $menu.find('.action-save-tree').prop('disabled', true);
            }
        };

        // Confirm navigating away from the page with unsaved changes
        window.onbeforeunload = function () {
            if (isDirty && !isSaving) {
                return "You have unsaved changes, are you sure you want to discard them?";
            } else {
                return;
            }
        };

        treeView = $menu.find('.tree').kendoTreeView({
            template: kendo.template($menu.find('.item-template').html()),
            dragAndDrop: true,
            autoScroll: true,
            drag: validateDrag,
            dragend: function () {
                dirty(true);
            },
            dataTextField: 'name',
            loadOnDemand: false,
            dataSource: {
                data: $menu.data('current-json'),
                schema: {
                    model: Item
                },
                change: dataChange
            },
            dataBound: dataBound
        }).data('kendoTreeView');
        treeView.templates.dragClue = kendo.template($menu.find('.drag-clue-template').html());

        $menu.find('.action-new-category').click(function () {
            showEditModalForItem($editModal, {
                kind: 'category',
                userItem: true
            });
        });

        $menu.find('.action-expand-all').click(function () {
            treeView.expand(".k-item");
        });

        $menu.find('.action-collapse-all').click(function () {
            treeView.collapse(".k-item");
        });

        $menu.find('.action-reset-defaults').click(function () {
            var $this;
            $this = $(this);
            $this.prop('disabled', true);
            $resetDefaultsModal.modal('show');
        });

        $menu.find('.action-reset-saved').click(function () {
            var $this;
            $this = $(this);
            $this.prop('disabled', true);
            $resetSavedModal.modal('show');
        });

        $menu.find('.action-save-tree').click(function () {
            isSaving = true;
            treeView.enable('.k-item', false);
            saveChanges(treeView, $menu);
            dirty(false);
        });

        $menu.on('click', '.item-category', function () {
            var $node;
            $node = $(this).closest('.k-item');
            if ($node.attr('aria-disabled') === 'true') {
                return false;
            }
            treeView.toggle($node);
        });

        $menu.on('click', '.action-edit', function () {
            var $node, item;
            $node = $(this).closest('.k-item');
            if ($node.attr('aria-disabled') === 'true') {
                return false;
            }
            item = treeView.dataItem($node);
            showEditModalForItem($editModal, item);
        });

        $editModal.on('shown.bs.modal', function () {
            $editModal.find('.item-name').focus();
        });

        $editModal.find('.action-modal-save').click(function () {
            var $this, item, success, uid;
            $this = $(this);
            $this.prop('disabled', true);
            uid = $editModal.data('uid');
            if (uid != null) {
                item = treeView.dataSource.getByUid(uid);
                success = updateItemForModal($editModal, item);
            } else {
                success = createItemForModal($editModal, treeView);
            }
            if (success) {
                $editModal.modal('hide');
                dirty(true);
            }
            $this.prop('disabled', false);
        });

        $editModal.find('.action-delete').click(function () {
            var item, uid;
            uid = $editModal.data('uid');
            item = treeView.dataSource.getByUid(uid);
            showDeleteModalForItem($deleteModal, item);
        });

        $deleteModal.find('.action-modal-delete').click(function () {
            var $this, item, uid;
            $this = $(this);
            $this.prop('disabled', true);
            uid = $deleteModal.data('uid');
            if (uid != null) {
                item = treeView.dataSource.getByUid(uid);
                deleteItem(treeView, item);
                dirty(true);
            }
            $deleteModal.modal('hide');
            $this.prop('disabled', false);
        });

        $resetDefaultsModal.on('shown.bs.modal', function () {
            $resetDefaultsModal.find('.action-modal-cancel').focus();
        });

        $resetDefaultsModal.find('.action-modal-reset-defaults').click(function () {
            var $this;
            $this = $(this);

            $menu.data('current-json', $menu.data('defaults-json'));
            treeView.dataSource.data($menu.data('current-json'));

            $resetDefaultsModal.modal('hide');
            $this.prop('disabled', false);
            dirty(true);
        });

        $resetSavedModal.on('shown.bs.modal', function () {
            $resetSavedModal.find('.action-modal-cancel').focus();
        });

        $resetSavedModal.find('.action-modal-reset-saved').click(function () {
            var $this;
            $this = $(this);

            $menu.data('current-json', $menu.data('json'));
            treeView.dataSource.data($menu.data('current-json'));

            $resetSavedModal.modal('hide');
            $this.prop('disabled', false);
            dirty(false);
        });
    });

}).call(this);
