/**
 * MetaPlayer 1.0
 * 
 * Licensed under the GPL terms
 * To use it on other terms please contact us
 *
 * Copyright(c) 2010-2011 Val Dubrava [ valery.dubrava@gmail.com ] 
 * 
 */
/*global AlbumNode:false, BandNode:false, TrackNode:false */
/***************************************
 *********** BaseRepository ************
 ***************************************/
function BaseRepository() {
    this.url = undefined;
    this.nodeLoadedEvent = "nodeLoaded";
    this.nodeUpdatedEvent = "nodeUpdated";
    this.nodeRemovedEvent = "nodeRemoved";
    this.identityMap = [];
}

BaseRepository.prototype.constructor = BaseRepository;

BaseRepository.prototype.bindOnLoaded = function (handler) {
    $(this).bind(this.nodeLoadedEvent, handler);
};
BaseRepository.prototype.bindOnUpdated = function (handler) {
    $(this).bind(this.nodeUpdatedEvent, handler);
};
BaseRepository.prototype.bindOnRemoved = function (handler) {
    $(this).bind(this.nodeRemovedEvent, handler);
};
/**
 * Checks if this repository of the specified entity.
 * @param entity
 */
BaseRepository.prototype.isRepositoryOf = function (entity) {
    throw "All inheritance of BaseRepository must implement method isRepositoryOf.";
};

BaseRepository.prototype.dispatch = function (data) {
    if (!$.isArray(data)) {
        data = [data];
    }
    var loaded = [];
    var updated = [];

    for (var index = 0; index < data.length; index ++) {
        var entity = data[index];
        var identity = entity.id.toString();
        var oldEntity = this.identityMap[identity];
        var isNew = (oldEntity === undefined);
        if (isNew) {
            this.identityMap[identity] = entity;
            loaded.push(entity);
        } else {
            console.log("update existing entity with new data", $.extend({}, oldEntity), entity);
            $.extend(oldEntity, entity);
            updated.push(oldEntity);
        }
    }
    if (loaded.length > 0) {
        $(this).trigger(this.nodeLoadedEvent, [loaded]);
    }
    if (updated.length > 0) {
        $(this).trigger(this.nodeUpdatedEvent, [updated]);
    }
};

BaseRepository.prototype.dispatchRemove = function (node) {
    var identity = node.id.toString();
    this.identityMap[identity] = undefined;
    $(this).trigger(this.nodeRemovedEvent, [node]);
};

BaseRepository.prototype.list = function (success, params) {
    var url = this.url + 'list';
    var that = this;
    $.getJSON(url, params, function (data, textStatus, jqXHR) {
        var parsedData = $.parseJSONObject(data);
        that.dispatch(parsedData);
        if ($.isFunction(success)) {
            success(parsedData);
        }
    });
};

BaseRepository.prototype.get = function (id, success) {
    var url = this.url + 'get';
    var that = this;
    $.getJSON(url, {"id": id}, function (data, textStatus, jqXHR) {
        var parsedData = $.parseJSONObject(data);
        that.dispatch(parsedData);
        if ($.isFunction(success)) {
            success(parsedData);
        }
    });
};

BaseRepository.prototype.add = function (node, success) {
    var url = this.url + 'add';
    var that = this;
    var data = $.objectToJSON(node);

    $.post(url, {"json": data}, function (result, textStatus, jqXHR) {
        var parsedData = $.parseJSONObject(result);
        that.dispatch(parsedData);
        if ($.isFunction(success)) {
            success(parsedData, node);
        }
    }, "json");
};

BaseRepository.prototype.addOrGet = function (node, success, error) {
    var url = this.url + 'addOrGet';
    var that = this;
    var data = $.objectToJSON(node);

    $.ajax({
        type: 'POST',
        url: url,
        data: {"json": data},
        dataType: 'json',
            success: function (result, textStatus, jqXHR) {
            var parsedData = null;
            try {
                parsedData = $.parseJSONObject(result);
                that.dispatch(parsedData);
            } catch(e) {
                if ($.isFunction(error)) {
                    error(jqXHR, textStatus, error);
                }
            }
            if (parsedData && $.isFunction(success)) {
                success(parsedData, node);
            }
        },
        error: error
    });
};

BaseRepository.prototype.remove = function (node, success) {
    var url = this.url + 'remove';
    var that = this;
    var id = node.getServerId();

    $.post(url, {"id": id}, function (result, textStatus, jqXHR) {
        that.dispatchRemove(node);
        if ($.isFunction(success)) {
            success(node);
        }
    });
};

BaseRepository.prototype.update = function (node, success) {
    var url = this.url + 'update'; // &XDEBUG_SESSION=idea
    var that = this;
    var data = $.objectToJSON(node);
    $.post(url, {"json": data}, function (result, textStatus, jqXHR) {
        var parsedData = $.parseJSONObject(result);
        that.dispatch(parsedData);
        if ($.isFunction(success)) {
            success(parsedData, node);
        }
    }, "json");
};

/***************************************
 *********** BandRepository ************
 ***************************************/
function BandRepository() {
    this.parent();
    this.url = '/band/';
    this.counter ++;
}

BandRepository.prototype = new BaseRepository();
BandRepository.prototype.parent = BaseRepository;
BandRepository.prototype.counter = 0;
BandRepository.prototype.isRepositoryOf = function (object) {
    return object instanceof BandNode;
};
var bandRepository = new BandRepository();

/***************************************
 *********** AlbumRepository ***********
 ***************************************/
function AlbumRepository() {
    this.parent();
    this.url = '/album/';
}

AlbumRepository.prototype = new BaseRepository();
AlbumRepository.prototype.parent = BaseRepository;
AlbumRepository.prototype.isRepositoryOf = function (entity) {
    return entity instanceof AlbumNode;
};
var albumRepository = new AlbumRepository();

/***************************************
 *********** TrackRepository ***********
 ***************************************/
function TrackRepository() {
    this.parent();
    this.url = '/track/';
}

TrackRepository.prototype = new BaseRepository();
TrackRepository.prototype.parent = BaseRepository;
TrackRepository.prototype.isRepositoryOf = function (entity) {
    return entity instanceof TrackNode;
};
var trackRepository = new TrackRepository();

/**
 * Array of all repositories.
 */
var repositories = [bandRepository, albumRepository, trackRepository];

/**
 * Returns repository for the specified entity.
 * @param entity
 */
function getRepositoryFor(entity) {
    for (var i = 0; i < repositories.length; i ++) {
        var repository = repositories[i];
        if (repository.isRepositoryOf(entity)) {
            return repository;
        }
    }
    throw "There is no repository for entity " + entity;
}
/***************************************
 ******** AssociationRepository ********
 ***************************************/
function AssociationRepository() {
    this.url = '/association/';
}
AssociationRepository.prototype.associate = function (track, association, handler) {
    var url = this.url + 'associate';
    var data = $.objectToJSON(association);
    $.post(url, {trackId: track.getServerId(), 'json': data}, function (result, textStatus, jqXHR) {
        var serverAssociation = $.parseJSONObject(result);
        track.setAssociation(serverAssociation);
        // invoke update action
        trackRepository.dispatch(track);
        if (handler && $.isFunction(handler)) {
            handler(track, serverAssociation);
        }
    });
};

var associationRepository = new AssociationRepository();