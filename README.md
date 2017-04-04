# QueryBuilderRepositoryGeneratorBundle
The QueryBuilderRepositoryGeneratorBundle generates Repositories in the repository folders

The functions allow to filter on the columns of the entity with a query builder.

See the create queries section for an example.

# Installation

## Import the bundle using composer
    "tbn/query-builder-repository-generator-bundle"
## Import the bundle in your AppKernel
    new tbn\QueryBuilderRepositoryGeneratorBundle\QueryBuilderRepositoryGeneratorBundle(),

# Configuration

## Mandatory configuration
Add the bundles you want:

		query_builder_repository_generator:
    		bundles:
        		- "<<YourBundleName>>"
Those bundles will have the repositories generated in the Repository directory of the bundles. Check the content by yourself.

## Optional configuration

		query_builder_repository_generator:
			templates:
				top_repository: "QueryBuilderRepositoryGeneratorBundle:Generator:TopRepositoryTemplate.html.twig"
				column: "QueryBuilderRepositoryGeneratorBundle:Generator:ColumnTemplate.html.twig"
				association: "QueryBuilderRepositoryGeneratorBundle:Generator:AssociationTemplate.html.twig"
				bottom_repository: "QueryBuilderRepositoryGeneratorBundle:Generator:BottomRepositoryTemplate.html.twig"
		mapping:
			- 'AppBundle\Entity\Member':
				querybuilder_name: 'memberEntity' # the name of the entity used in the queryBuilder

### The repository extends

You can specify a class to extends for each entity.


		repositories_extensions:
			- "AcmeBundle\Entity\Item": #the entity class
				extension_class: "\\Gedmo\\Tree\\Entity\\Repository\\MaterializedPathRepository" #the class to extends

The templates used by the generator can be set with these configurations.

		top_repository => The beginning of the repository file
		column => The template used for each column
		extra_column => A custom template of your choice
		bottom_repository => The end of the repository file

The extra_column template have the following variables:

		'entity' => $tableName,
		'entityDql' => lcfirst($tableName),
		'column' => ucfirst($columnName),
		'columnDql' => $columnName

# Use generated repositories

In your Entity Repository, extends the generated repository.

		class UserRepository extends UserRepositoryBase

Your repository has now some predefined function like "filterById", "filterInId" for all the columns.

# Create queries

 Example:

		$qb = $this->createQueryBuilder('document');

		//filter on current user (where XX = YY)
       DocumentRepository::filterByUser($qb, $user);
       //filter on the extension list (where xxx IN () )
       DocumentRepository::filterInExtension($qb, $extensionList);

       //Join the tag entity
       $qb->join('document.tags', 'tag');
       //filter on the tag entity
       TagRepository::filterById($qb, $tagButton);

# Regenerate files

Run the command:

    php app/console qbrg:generate
