const FilterItem = require( './FilterItem.js' ),
	utils = require( '../utils.js' );

/**
 * View model for a filter group.
 *
 * @class mw.rcfilters.dm.FilterGroup
 * @ignore
 * @mixes OO.EventEmitter
 * @mixes OO.EmitterList
 *
 * @param {string} name Group name
 * @param {Object} [config] Configuration options
 * @param {string} [config.type='send_unselected_if_any'] Group type
 * @param {string} [config.view='default'] Name of the display group this group
 *  is a part of.
 * @param {boolean} [config.sticky] This group is 'sticky'. It is synchronized
 *  with a preference, does not participate in Saved Queries, and is
 *  not shown in the active filters area.
 * @param {string} [config.title] Group title
 * @param {boolean} [config.hidden] This group is hidden from the regular menu views
 *  and the active filters area.
 * @param {boolean} [config.allowArbitrary] Allows for an arbitrary value to be added to the
 *  group from the URL, even if it wasn't initially set up.
 * @param {number} [config.range] An object defining minimum and maximum values for numeric
 *  groups. { min: x, max: y }
 * @param {number} [config.minValue] Minimum value for numeric groups
 * @param {string} [config.separator='|'] Value separator for 'string_options' groups
 * @param {boolean} [config.supportsAll=true] For 'string_options' groups, whether the magic 'all' value
 *  is understood to mean all options are selected.
 * @param {boolean} [config.active] Group is active
 * @param {boolean} [config.fullCoverage] This filters in this group collectively cover all results
 * @param {Object} [config.conflicts] Defines the conflicts for this filter group
 * @param {string|Object} [config.labelPrefixKey] An i18n key defining the prefix label for this
 *  group. If the prefix has 'invert' state, the parameter is expected to be an object
 *  with 'default' and 'inverted' as keys.
 * @param {Object} [config.whatsThis] Defines the messages that should appear for the 'what's this' popup
 * @param {string} [config.whatsThis.header] The header of the whatsThis popup message
 * @param {string} [config.whatsThis.body] The body of the whatsThis popup message
 * @param {string} [config.whatsThis.url] The url for the link in the whatsThis popup message
 * @param {string} [config.whatsThis.linkMessage] The text for the link in the whatsThis popup message
 * @param {boolean} [config.visible=true] The visibility of the group
 */
const FilterGroup = function MwRcfiltersDmFilterGroup( name, config ) {
	config = config || {};

	// Mixin constructor
	OO.EventEmitter.call( this );
	OO.EmitterList.call( this );

	this.name = name;
	this.type = config.type || 'send_unselected_if_any';
	this.view = config.view || 'default';
	this.sticky = !!config.sticky;
	this.title = config.title || name;
	this.hidden = !!config.hidden;
	this.allowArbitrary = !!config.allowArbitrary;
	this.numericRange = config.range;
	this.separator = config.separator || '|';
	this.supportsAll = config.supportsAll === undefined ? true : !!config.supportsAll;
	this.labelPrefixKey = config.labelPrefixKey;
	this.visible = config.visible === undefined ? true : !!config.visible;

	this.currSelected = null;
	this.active = !!config.active;
	this.fullCoverage = !!config.fullCoverage;

	this.whatsThis = config.whatsThis || {};

	this.conflicts = config.conflicts || {};
	this.defaultParams = {};
	this.defaultFilters = {};

	this.aggregate( { update: 'filterItemUpdate' } );
	this.connect( this, { filterItemUpdate: 'onFilterItemUpdate' } );
};

/* Initialization */
OO.initClass( FilterGroup );
OO.mixinClass( FilterGroup, OO.EventEmitter );
OO.mixinClass( FilterGroup, OO.EmitterList );

/* Events */

/**
 * Group state has been updated.
 *
 * @event update
 * @ignore
 */

/* Methods */

/**
 * Initialize the group and create its filter items
 *
 * @param {Object} filterDefinition Filter definition for this group
 * @param {string|Object} [groupDefault] Definition of the group default
 */
FilterGroup.prototype.initializeFilters = function ( filterDefinition, groupDefault ) {
	let defaultParam;
	const supersetMap = {},
		items = [];

	filterDefinition.forEach( ( filter ) => {
		// Instantiate an item
		const filterItem = new FilterItem( filter.name, this, {
			group: this.getName(),
			label: filter.label || filter.name,
			description: filter.description || '',
			labelPrefixKey: this.labelPrefixKey,
			cssClass: filter.cssClass,
			helpLink: filter.helpLink,
			identifiers: filter.identifiers,
			defaultHighlightColor: filter.defaultHighlightColor
		} );

		if ( filter.subset ) {
			filter.subset = filter.subset.map( ( el ) => el.filter );

			const subsetNames = [];

			filter.subset.forEach( ( subsetFilterName ) => {
				// Subsets (unlike conflicts) are always inside the same group
				// We can re-map the names of the filters we are getting from
				// the subsets with the group prefix
				const subsetName = this.getPrefixedName( subsetFilterName );
				// For convenience, we should store each filter's "supersets" -- these are
				// the filters that have that item in their subset list. This will just
				// make it easier to go through whether the item has any other items
				// that affect it (and are selected) at any given time
				supersetMap[ subsetName ] = supersetMap[ subsetName ] || [];
				utils.addArrayElementsUnique(
					supersetMap[ subsetName ],
					filterItem.getName()
				);

				// Translate subset param name to add the group name, so we
				// get consistent naming. We know that subsets are only within
				// the same group
				subsetNames.push( subsetName );
			} );

			// Set translated subset
			filterItem.setSubset( subsetNames );
		}

		items.push( filterItem );

		// Store default parameter state; in this case, default is defined per filter
		if (
			this.getType() === 'send_unselected_if_any' ||
			this.getType() === 'boolean'
		) {
			// Store the default parameter state
			// For this group type, parameter values are direct
			// We need to convert from a boolean to a string ('1' and '0')
			this.defaultParams[ filter.name ] = String( Number( filter.default || 0 ) );
		} else if ( this.getType() === 'any_value' ) {
			this.defaultParams[ filter.name ] = filter.default;
		}
	} );

	// Add items
	this.addItems( items );

	// Now that we have all items, we can apply the superset map
	this.getItems().forEach( ( filterItem ) => {
		filterItem.setSuperset( supersetMap[ filterItem.getName() ] );
	} );

	// Store default parameter state; in this case, default is defined per the
	// entire group, given by groupDefault method parameter
	if ( this.getType() === 'string_options' ) {
		// Store the default parameter group state
		// For this group, the parameter is group name and value is the names
		// of selected items
		this.defaultParams[ this.getName() ] = utils.normalizeParamOptions(
			// Current values
			groupDefault ?
				groupDefault.split( this.getSeparator() ) :
				[],
			// Legal values
			this.getItems().map( ( item ) => item.getParamName() )
		).join( this.getSeparator() );
	} else if ( this.getType() === 'single_option' ) {
		defaultParam = groupDefault !== undefined ?
			groupDefault : this.getItems()[ 0 ].getParamName();

		// For this group, the parameter is the group name,
		// and a single item can be selected: default or first item
		this.defaultParams[ this.getName() ] = defaultParam;
	}

	// add highlights to defaultParams
	this.getItems().forEach( ( filterItem ) => {
		if ( filterItem.isHighlighted() ) {
			this.defaultParams[ filterItem.getName() + '_color' ] = filterItem.getHighlightColor();
		}
	} );

	// Store default filter state based on default params
	this.defaultFilters = this.getFilterRepresentation( this.getDefaultParams() );

	// Check for filters that should be initially selected by their default value
	if ( this.isSticky() ) {
		const defaultFilters = this.defaultFilters;
		for ( const filterName in defaultFilters ) {
			const filterValue = defaultFilters[ filterName ];
			this.getItemByName( filterName ).toggleSelected( filterValue );
		}
	}

	// Verify that single_option group has at least one item selected
	if (
		this.getType() === 'single_option' &&
		this.findSelectedItems().length === 0
	) {
		defaultParam = groupDefault !== undefined ?
			groupDefault : this.getItems()[ 0 ].getParamName();

		// Single option means there must be a single option
		// selected, so we have to either select the default
		// or select the first option
		this.selectItemByParamName( defaultParam );
	}
};

/**
 * Respond to filterItem update event
 *
 * @param {mw.rcfilters.dm.FilterItem} item Updated filter item
 * @fires update
 */
FilterGroup.prototype.onFilterItemUpdate = function ( item ) {
	// Update state
	let changed = false;
	const active = this.areAnySelected();

	if ( this.getType() === 'single_option' ) {
		// This group must have one item selected always
		// and must never have more than one item selected at a time
		if ( this.findSelectedItems().length === 0 ) {
			// Nothing is selected anymore
			// Select the default or the first item
			this.currSelected = this.getItemByParamName( this.defaultParams[ this.getName() ] ) ||
				this.getItems()[ 0 ];
			this.currSelected.toggleSelected( true );
			changed = true;
		} else if ( this.findSelectedItems().length > 1 ) {
			// There is more than one item selected
			// This should only happen if the item given
			// is the one that is selected, so unselect
			// all items that is not it
			this.findSelectedItems().forEach( ( itemModel ) => {
				// Note that in case the given item is actually
				// not selected, this loop will end up unselecting
				// all items, which would trigger the case above
				// when the last item is unselected anyways
				const selected = itemModel.getName() === item.getName() &&
					item.isSelected();

				itemModel.toggleSelected( selected );
				if ( selected ) {
					this.currSelected = itemModel;
				}
			} );
			changed = true;
		}
	}

	if ( this.isSticky() ) {
		// If this group is sticky, then change the default according to the
		// current selection.
		this.defaultParams = this.getParamRepresentation( this.getSelectedState() );
	}

	if (
		changed ||
		this.active !== active ||
		this.currSelected !== item
	) {
		this.active = active;
		this.currSelected = item;

		this.emit( 'update' );
	}
};

/**
 * Get group active state
 *
 * @return {boolean} Active state
 */
FilterGroup.prototype.isActive = function () {
	return this.active;
};

/**
 * Get group hidden state
 *
 * @return {boolean} Hidden state
 */
FilterGroup.prototype.isHidden = function () {
	return this.hidden;
};

/**
 * Get group allow arbitrary state
 *
 * @return {boolean} Group allows an arbitrary value from the URL
 */
FilterGroup.prototype.isAllowArbitrary = function () {
	return this.allowArbitrary;
};

/**
 * Get group maximum value for numeric groups
 *
 * @return {number|null} Group max value
 */
FilterGroup.prototype.getMaxValue = function () {
	return this.numericRange && this.numericRange.max !== undefined ?
		this.numericRange.max : null;
};

/**
 * Get group minimum value for numeric groups
 *
 * @return {number|null} Group max value
 */
FilterGroup.prototype.getMinValue = function () {
	return this.numericRange && this.numericRange.min !== undefined ?
		this.numericRange.min : null;
};

/**
 * Get group name
 *
 * @return {string} Group name
 */
FilterGroup.prototype.getName = function () {
	return this.name;
};

/**
 * Get the default param state of this group
 *
 * @return {Object} Default param state
 */
FilterGroup.prototype.getDefaultParams = function () {
	return this.defaultParams;
};

/**
 * Get the default filter state of this group
 *
 * @return {Object} Default filter state
 */
FilterGroup.prototype.getDefaultFilters = function () {
	return this.defaultFilters;
};

/**
 * Get the messags defining the 'whats this' popup for this group
 *
 * @return {Object} What's this messages
 */
FilterGroup.prototype.getWhatsThis = function () {
	return this.whatsThis;
};

/**
 * Check whether this group has a 'what's this' message
 *
 * @return {boolean} This group has a what's this message
 */
FilterGroup.prototype.hasWhatsThis = function () {
	return !!this.whatsThis.body;
};

/**
 * Get the conflicts associated with the entire group.
 *
 * Conflict object is set up by filter name keys and conflict
 * definition.
 *
 * @example
 * [
 *     {
 *         filterName: {
 *             filter: filterName,
 *             group: group1
 *         }
 *     },
 *     {
 *         filterName2: {
 *             filter: filterName2,
 *             group: group2
 *         }
 *     }
 * ]
 *
 * @return {Object} Conflict definition
 */
FilterGroup.prototype.getConflicts = function () {
	return this.conflicts;
};

/**
 * Set conflicts for this group. See #getConflicts for the expected
 * structure of the definition.
 *
 * @param {Object} conflicts Conflicts for this group
 */
FilterGroup.prototype.setConflicts = function ( conflicts ) {
	this.conflicts = conflicts;
};

/**
 * Check whether this item has a potential conflict with the given item
 *
 * This checks whether the given item is in the list of conflicts of
 * the current item, but makes no judgment about whether the conflict
 * is currently at play (either one of the items may not be selected)
 *
 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter item
 * @return {boolean} This item has a conflict with the given item
 */
FilterGroup.prototype.existsInConflicts = function ( filterItem ) {
	return Object.prototype.hasOwnProperty.call( this.getConflicts(), filterItem.getName() );
};

/**
 * Check whether there are any items selected
 *
 * @return {boolean} Any items in the group are selected
 */
FilterGroup.prototype.areAnySelected = function () {
	return this.getItems().some( ( filterItem ) => filterItem.isSelected() );
};

/**
 * Check whether all items selected
 *
 * @return {boolean} All items are selected
 */
FilterGroup.prototype.areAllSelected = function () {
	const selected = [],
		unselected = [];

	this.getItems().forEach( ( filterItem ) => {
		if ( filterItem.isSelected() ) {
			selected.push( filterItem );
		} else {
			unselected.push( filterItem );
		}
	} );

	if ( unselected.length === 0 ) {
		return true;
	}

	// check if every unselected is a subset of a selected
	return unselected.every( ( unselectedFilterItem ) => selected.some( ( selectedFilterItem ) => selectedFilterItem.existsInSubset( unselectedFilterItem.getName() ) ) );
};

/**
 * Get all selected items in this group
 *
 * @ignore
 * @param {mw.rcfilters.dm.FilterItem} [excludeItem] Item to exclude from the list
 * @return {mw.rcfilters.dm.FilterItem[]} Selected items
 */
FilterGroup.prototype.findSelectedItems = function ( excludeItem ) {
	const excludeName = ( excludeItem && excludeItem.getName() ) || '';

	return this.getItems().filter( ( item ) => item.getName() !== excludeName && item.isSelected() );
};

/**
 * Check whether all selected items are in conflict with the given item
 *
 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter item to test
 * @return {boolean} All selected items are in conflict with this item
 */
FilterGroup.prototype.areAllSelectedInConflictWith = function ( filterItem ) {
	const selectedItems = this.findSelectedItems( filterItem );

	return selectedItems.length > 0 &&
		(
			// The group as a whole is in conflict with this item
			this.existsInConflicts( filterItem ) ||
			// All selected items are in conflict individually
			selectedItems.every( ( selectedFilter ) => selectedFilter.existsInConflicts( filterItem ) )
		);
};

/**
 * Check whether any of the selected items are in conflict with the given item
 *
 * @param {mw.rcfilters.dm.FilterItem} filterItem Filter item to test
 * @return {boolean} Any of the selected items are in conflict with this item
 */
FilterGroup.prototype.areAnySelectedInConflictWith = function ( filterItem ) {
	const selectedItems = this.findSelectedItems( filterItem );

	return selectedItems.length > 0 && (
		// The group as a whole is in conflict with this item
		this.existsInConflicts( filterItem ) ||
		// Any selected items are in conflict individually
		selectedItems.some( ( selectedFilter ) => selectedFilter.existsInConflicts( filterItem ) )
	);
};

/**
 * Get the parameter representation from this group
 *
 * @param {Object} [filterRepresentation] An object defining the state
 *  of the filters in this group, keyed by their name and current selected
 *  state value.
 * @return {Object} Parameter representation
 */
FilterGroup.prototype.getParamRepresentation = function ( filterRepresentation ) {
	let areAnySelected = false;
	const buildFromCurrentState = !filterRepresentation,
		defaultFilters = this.getDefaultFilters(),
		result = {},
		filterParamNames = {},
		getSelectedParameter = ( filters ) => {
			const selected = [];

			// Find if any are selected
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( filters, ( name, value ) => {
				if ( value ) {
					selected.push( name );
				}
			} );

			const item = this.getItemByName( selected[ 0 ] );
			return ( item && item.getParamName() ) || '';
		};

	filterRepresentation = filterRepresentation || {};

	// Create or complete the filterRepresentation definition
	this.getItems().forEach( ( item ) => {
		// Map filter names to their parameter names
		filterParamNames[ item.getName() ] = item.getParamName();

		if ( buildFromCurrentState ) {
			// This means we have not been given a filter representation
			// so we are building one based on current state
			filterRepresentation[ item.getName() ] = item.getValue();
		} else if ( filterRepresentation[ item.getName() ] === undefined ) {
			// We are given a filter representation, but we have to make
			// sure that we fill in the missing filters if there are any
			// we will assume they are all falsey
			if ( this.isSticky() ) {
				filterRepresentation[ item.getName() ] = !!defaultFilters[ item.getName() ];
			} else {
				filterRepresentation[ item.getName() ] = false;
			}
		}

		if ( filterRepresentation[ item.getName() ] ) {
			areAnySelected = true;
		}
	} );

	// Build result
	if (
		this.getType() === 'send_unselected_if_any' ||
		this.getType() === 'boolean' ||
		this.getType() === 'any_value'
	) {
		// First, check if any of the items are selected at all.
		// If none is selected, we're treating it as if they are
		// all false

		// Go over the items and define the correct values
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( filterRepresentation, ( name, value ) => {
			// We must store all parameter values as strings '0' or '1'
			if ( this.getType() === 'send_unselected_if_any' ) {
				result[ filterParamNames[ name ] ] = areAnySelected ?
					String( Number( !value ) ) :
					'0';
			} else if ( this.getType() === 'boolean' ) {
				// Representation is straight-forward and direct from
				// the parameter value to the filter state
				result[ filterParamNames[ name ] ] = String( Number( !!value ) );
			} else if ( this.getType() === 'any_value' ) {
				result[ filterParamNames[ name ] ] = value;
			}
		} );
	} else if ( this.getType() === 'string_options' ) {
		const values = [];

		// eslint-disable-next-line no-jquery/no-each-util
		$.each( filterRepresentation, ( name, value ) => {
			// Collect values
			if ( value ) {
				values.push( filterParamNames[ name ] );
			}
		} );

		result[ this.getName() ] = this.getSupportsAll() &&
				values.length === Object.keys( filterRepresentation ).length ?
			'all' : values.join( this.getSeparator() );
	} else if ( this.getType() === 'single_option' ) {
		result[ this.getName() ] = getSelectedParameter( filterRepresentation );
	}

	return result;
};

/**
 * Get the filter representation this group would provide
 * based on given parameter states.
 *
 * @param {Object} [paramRepresentation] An object defining a parameter
 *  state to translate the filter state from. If not given, an object
 *  representing all filters as falsey is returned; same as if the parameter
 *  given were an empty object, or had some of the filters missing.
 * @return {Object} Filter representation
 */
FilterGroup.prototype.getFilterRepresentation = function ( paramRepresentation ) {
	let areAnySelected,
		oneWasSelected = false;
	const defaultParams = this.getDefaultParams(),
		expandedParams = $.extend( true, {}, paramRepresentation ),
		paramToFilterMap = {},
		result = {};

	if ( this.isSticky() ) {
		// If the group is sticky, check if all parameters are represented
		// and for those that aren't represented, add them with their default
		// values
		paramRepresentation = $.extend( true, {}, this.getDefaultParams(), paramRepresentation );
	}

	paramRepresentation = paramRepresentation || {};
	if (
		this.getType() === 'send_unselected_if_any' ||
		this.getType() === 'boolean' ||
		this.getType() === 'any_value'
	) {
		// Go over param representation; map and check for selections
		this.getItems().forEach( ( filterItem ) => {
			const paramName = filterItem.getParamName();

			expandedParams[ paramName ] = paramRepresentation[ paramName ] || '0';
			paramToFilterMap[ paramName ] = filterItem;

			if ( Number( paramRepresentation[ filterItem.getParamName() ] ) ) {
				areAnySelected = true;
			}
		} );

		// eslint-disable-next-line no-jquery/no-each-util
		$.each( expandedParams, ( paramName, paramValue ) => {
			const filterItem = paramToFilterMap[ paramName ];

			if ( this.getType() === 'send_unselected_if_any' ) {
				// Flip the definition between the parameter
				// state and the filter state
				// This is what the 'toggleSelected' value of the filter is
				result[ filterItem.getName() ] = areAnySelected ?
					!Number( paramValue ) :
					// Otherwise, there are no selected items in the
					// group, which means the state is false
					false;
			} else if ( this.getType() === 'boolean' ) {
				// Straight-forward definition of state
				result[ filterItem.getName() ] = !!Number( paramRepresentation[ filterItem.getParamName() ] );
			} else if ( this.getType() === 'any_value' ) {
				result[ filterItem.getName() ] = paramRepresentation[ filterItem.getParamName() ];
			}
		} );
	} else if ( this.getType() === 'string_options' ) {
		const currentValue = paramRepresentation[ this.getName() ] || '';

		// Normalize the given parameter values
		const paramValues = utils.normalizeParamOptions(
			// Given
			currentValue.split(
				this.getSeparator()
			),
			// Allowed values
			this.getItems().map( ( filterItem ) => filterItem.getParamName() ),
			this.getSupportsAll()
		);
		// Translate the parameter values into a filter selection state
		this.getItems().forEach( ( filterItem ) => {
			// If the parameter is set to 'all', set all filters to true
			result[ filterItem.getName() ] = (
				this.getSupportsAll() && paramValues.length === 1 && paramValues[ 0 ] === 'all'
			) ?
				true :
				// Otherwise, the filter is selected only if it appears in the parameter values
				paramValues.includes( filterItem.getParamName() );
		} );
	} else if ( this.getType() === 'single_option' ) {
		// There is parameter that fits a single filter and if not, get the default
		this.getItems().forEach( ( filterItem ) => {
			const selected = filterItem.getParamName() === paramRepresentation[ this.getName() ];

			result[ filterItem.getName() ] = selected;
			oneWasSelected = oneWasSelected || selected;
		} );
	}

	// Go over result and make sure all filters are represented.
	// If any filters are missing, they will get a falsey value
	this.getItems().forEach( ( filterItem ) => {
		if ( result[ filterItem.getName() ] === undefined ) {
			result[ filterItem.getName() ] = this.getFalsyValue();
		}
	} );

	// Make sure that at least one option is selected in
	// single_option groups, no matter what path was taken
	// If none was selected by the given definition, then
	// we need to select the one in the base state -- either
	// the default given, or the first item
	if (
		this.getType() === 'single_option' &&
		!oneWasSelected
	) {
		let item = this.getItems()[ 0 ];
		if ( defaultParams[ this.getName() ] ) {
			item = this.getItemByParamName( defaultParams[ this.getName() ] );
		}

		result[ item.getName() ] = true;
	}

	return result;
};

/**
 * @return {any} The appropriate falsy value for this group type
 */
FilterGroup.prototype.getFalsyValue = function () {
	return this.getType() === 'any_value' ? '' : false;
};

/**
 * Get current selected state of all filter items in this group
 *
 * @return {Object} Selected state
 */
FilterGroup.prototype.getSelectedState = function () {
	const state = {};

	this.getItems().forEach( ( filterItem ) => {
		state[ filterItem.getName() ] = filterItem.getValue();
	} );

	return state;
};

/**
 * Get item by its filter name
 *
 * @ignore
 * @param {string} filterName Filter name
 * @return {mw.rcfilters.dm.FilterItem} Filter item
 */
FilterGroup.prototype.getItemByName = function ( filterName ) {
	return this.getItems().filter( ( item ) => item.getName() === filterName )[ 0 ];
};

/**
 * Select an item by its parameter name
 *
 * @param {string} paramName Filter parameter name
 */
FilterGroup.prototype.selectItemByParamName = function ( paramName ) {
	this.getItems().forEach( ( item ) => {
		item.toggleSelected( item.getParamName() === String( paramName ) );
	} );
};

/**
 * Get item by its parameter name
 *
 * @ignore
 * @param {string} paramName Parameter name
 * @return {mw.rcfilters.dm.FilterItem} Filter item
 */
FilterGroup.prototype.getItemByParamName = function ( paramName ) {
	return this.getItems().filter( ( item ) => item.getParamName() === String( paramName ) )[ 0 ];
};

/**
 * Get group type
 *
 * @return {string} Group type
 */
FilterGroup.prototype.getType = function () {
	return this.type;
};

/**
 * Check whether this group is represented by a single parameter
 * or whether each item is its own parameter
 *
 * @return {boolean} This group is a single parameter
 */
FilterGroup.prototype.isPerGroupRequestParameter = function () {
	return (
		this.getType() === 'string_options' ||
		this.getType() === 'single_option'
	);
};

/**
 * Get display group
 *
 * @return {string} Display group
 */
FilterGroup.prototype.getView = function () {
	return this.view;
};

/**
 * Get the prefix used for the filter names inside this group.
 *
 * @return {string} Group prefix
 */
FilterGroup.prototype.getNamePrefix = function () {
	return this.getName() + '__';
};

/**
 * Get a filter name with the prefix used for the filter names inside this group.
 *
 * @param {string} name Filter name to prefix
 * @return {string} Group prefix
 */
FilterGroup.prototype.getPrefixedName = function ( name ) {
	return this.getNamePrefix() + name;
};

/**
 * Get group's title
 *
 * @return {string} Title
 */
FilterGroup.prototype.getTitle = function () {
	return this.title;
};

/**
 * Get group's values separator
 *
 * @return {string} Values separator
 */
FilterGroup.prototype.getSeparator = function () {
	return this.separator;
};

/**
 * Check whether the group supports the magic 'all' value to indicate that all values are selected.
 *
 * @return {boolean} Group supports the magic 'all' value
 */
FilterGroup.prototype.getSupportsAll = function () {
	return this.supportsAll;
};

/**
 * Check whether the group is defined as full coverage
 *
 * @return {boolean} Group is full coverage
 */
FilterGroup.prototype.isFullCoverage = function () {
	return this.fullCoverage;
};

/**
 * Check whether the group is defined as sticky default
 *
 * @return {boolean} Group is sticky default
 */
FilterGroup.prototype.isSticky = function () {
	return this.sticky;
};

/**
 * Normalize a value given to this group. This is mostly for correcting
 * arbitrary values for 'single option' groups, given by the user settings
 * or the URL that can go outside the limits that are allowed.
 *
 * @param  {string} value Given value
 * @return {string} Corrected value
 */
FilterGroup.prototype.normalizeArbitraryValue = function ( value ) {
	if (
		this.getType() === 'single_option' &&
		this.isAllowArbitrary()
	) {
		if (
			this.getMaxValue() !== null &&
			value > this.getMaxValue()
		) {
			// Change the value to the actual max value
			return String( this.getMaxValue() );
		} else if (
			this.getMinValue() !== null &&
			value < this.getMinValue()
		) {
			// Change the value to the actual min value
			return String( this.getMinValue() );
		}
	}

	return value;
};

/**
 * Toggle the visibility of this group
 *
 * @param {boolean} [isVisible] Item is visible
 */
FilterGroup.prototype.toggleVisible = function ( isVisible ) {
	isVisible = isVisible === undefined ? !this.visible : isVisible;

	if ( this.visible !== isVisible ) {
		this.visible = isVisible;
		this.emit( 'update' );
	}
};

/**
 * Check whether the group is visible
 *
 * @return {boolean} Group is visible
 */
FilterGroup.prototype.isVisible = function () {
	return this.visible;
};

/**
 * Set the visibility of the items under this group by the given items array
 *
 * @param {mw.rcfilters.dm.ItemModel[]} visibleItems An array of visible items
 */
FilterGroup.prototype.setVisibleItems = function ( visibleItems ) {
	this.getItems().forEach( ( itemModel ) => {
		itemModel.toggleVisible( visibleItems.includes( itemModel ) );
	} );
};

module.exports = FilterGroup;
